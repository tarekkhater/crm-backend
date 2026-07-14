<?php

namespace App\Services\Users;

use App\Models\InfoTradeUser;
use Illuminate\Support\Facades\Schema;

class UserWalletService
{
    protected static ?bool $hasRealDepositColumn = null;

    public static function hasRealDepositColumn(): bool
    {
        if (self::$hasRealDepositColumn === null) {
            self::$hasRealDepositColumn = Schema::hasColumn('info_trade_users', 'real_deposit');
        }

        return self::$hasRealDepositColumn;
    }

    /**
     * Normalize admin wallet type from API (aliases supported).
     */
    public static function normalizeType(string $type): ?string
    {
        $type = strtolower(trim($type));

        return match ($type) {
            'deposit', 'real', 'real_deposit' => 'deposit',
            'credit', 'awaiting_deposit', 'awaiting' => 'credit',
            'bonus', 'bouns' => 'bonus',
            'mup', 'fake' => 'mup',
            default => null,
        };
    }

    public static function allowedTypes(): array
    {
        return ['deposit', 'credit', 'awaiting_deposit', 'bonus', 'bouns', 'mup', 'fake'];
    }

    /**
     * Real deposit wallet (stored in real_deposit column).
     */
    public static function realDeposit(InfoTradeUser $info): float
    {
        if (self::hasRealDepositColumn()) {
            $raw = $info->getAttributes()['real_deposit'] ?? null;
            if ($raw !== null && $raw !== '') {
                return (float) $raw;
            }
        }

        // Pre-migration fallback: balance held real only.
        return (float) ($info->getAttributes()['balance'] ?? $info->balance ?? 0);
    }

    public static function credit(InfoTradeUser $info): float
    {
        return (float) ($info->awaiting_deposit ?? 0);
    }

    public static function bonus(InfoTradeUser $info): float
    {
        return (float) ($info->bonus ?? 0);
    }

    public static function mup(InfoTradeUser $info): float
    {
        return (float) $info->mup;
    }

    public static function storedBalance(InfoTradeUser $info): float
    {
        return (float) ($info->getAttributes()['balance'] ?? $info->balance ?? 0);
    }

    public static function componentSum(InfoTradeUser $info): float
    {
        return self::realDeposit($info) + self::bonus($info) + self::mup($info);
    }

    /**
     * Align real_deposit + bonus + mup with stored balance (legacy profits lived only on balance).
     */
    public static function reconcileDrift(InfoTradeUser $info, bool $persist = false): bool
    {
        if (!self::hasRealDepositColumn()) {
            return false;
        }

        $stored = round(self::storedBalance($info), 2);
        $sum = round(self::componentSum($info), 2);

        if (abs($stored - $sum) <= 0.009) {
            return false;
        }

        if ($stored > 0.009) {
            $info->real_deposit = max(0, round($stored - self::bonus($info) - self::mup($info), 2));
        }

        self::syncBalance($info);

        if ($persist && $info->exists && $info->getKey()) {
            $info->saveQuietly();
        }

        return true;
    }

    /**
     * Persist drift fix when serving APIs or mutating wallets.
     */
    public static function ensureSynced(InfoTradeUser $info): void
    {
        if (self::reconcileDrift($info, true)) {
            $info->refresh();
        }
    }

    /**
     * Main balance = real_deposit + bonus + mup (mirrored in balance column).
     */
    public static function mainBalance(?InfoTradeUser $info): float
    {
        if (!$info) {
            return 0.0;
        }

        if (self::hasRealDepositColumn()) {
            self::reconcileDrift($info, false);

            return round(self::componentSum($info), 2);
        }

        return round(self::storedBalance($info), 2);
    }

    public static function canAfford(?InfoTradeUser $info, float $amount): bool
    {
        if (!$info || $amount <= 0) {
            return $amount <= 0;
        }

        self::reconcileDrift($info, $info->exists && (bool) $info->getKey());

        return self::mainBalance($info) + 0.00001 >= $amount;
    }

    /**
     * Deduct from main wallet: real_deposit → mup → bonus, then sync balance.
     */
    public static function applyDebit(InfoTradeUser $info, float $amount): bool
    {
        $amount = round((float) $amount, 2);
        if ($amount <= 0) {
            return true;
        }

        self::reconcileDrift($info, $info->exists && (bool) $info->getKey());

        if (!self::canAfford($info, $amount)) {
            return false;
        }

        if (!self::hasRealDepositColumn()) {
            $info->balance = number_format(max(0, (float) ($info->balance ?? 0) - $amount), 2, '.', '');

            return true;
        }

        $remaining = $amount;

        $real = self::realDeposit($info);
        $fromReal = min($real, $remaining);
        $info->real_deposit = $real - $fromReal;
        $remaining -= $fromReal;

        if ($remaining > 0) {
            $mup = self::mup($info);
            $fromMup = min($mup, $remaining);
            $info->mup = $mup - $fromMup;
            $remaining -= $fromMup;
        }

        if ($remaining > 0) {
            $bonus = self::bonus($info);
            $fromBonus = min($bonus, $remaining);
            $info->bonus = $bonus - $fromBonus;
            $remaining -= $fromBonus;
        }

        self::syncBalance($info);

        return $remaining < 0.01;
    }

    /**
     * Zero all main wallet components (real, mup, bonus) and sync balance.
     */
    public static function resetMainWallets(InfoTradeUser $info): void
    {
        if (self::hasRealDepositColumn()) {
            $info->real_deposit = 0;
            $info->bonus = 0;
            $info->mup = 0;
            self::syncBalance($info);
        } else {
            $info->balance = '0.00';
        }
    }

    /**
     * Apply +/- to main wallet: credit → real_deposit; debit → real → mup → bonus.
     */
    public static function applyMainWalletDelta(InfoTradeUser $info, float $delta): bool
    {
        $delta = round((float) $delta, 2);
        if ($delta > 0) {
            self::applyCreditToMain($info, $delta);

            return true;
        }
        if ($delta < 0) {
            return self::applyDebit($info, abs($delta));
        }

        return true;
    }

    /**
     * Credit main trading balance (adds to real_deposit, then syncs balance).
     */
    public static function applyCreditToMain(InfoTradeUser $info, float $amount): void
    {
        $amount = round((float) $amount, 2);
        if ($amount <= 0) {
            return;
        }

        if (self::hasRealDepositColumn()) {
            $info->real_deposit = self::realDeposit($info) + $amount;
            self::syncBalance($info);
        } else {
            $info->balance = number_format((float) ($info->balance ?? 0) + $amount, 2, '.', '');
        }
    }

    /**
     * Persist balance = sum of components (writes — does not run legacy drift fix).
     */
    public static function syncBalance(InfoTradeUser $info): void
    {
        if (!self::hasRealDepositColumn()) {
            return;
        }

        $info->balance = number_format(self::componentSum($info), 2, '.', '');
    }

    public static function addMup(InfoTradeUser $info, float $amount): void
    {
        $info->mup = self::mup($info) + $amount;
        self::syncBalance($info);
    }

    /**
     * Admin sets absolute value for one wallet component; balance updates automatically.
     */
    public static function setAbsolute(InfoTradeUser $info, string $type, float $value): void
    {
        $normalized = self::normalizeType($type) ?? $type;

        switch ($normalized) {
            case 'deposit':
                if (self::hasRealDepositColumn()) {
                    $info->real_deposit = $value;
                    self::syncBalance($info);
                } else {
                    $info->balance = number_format($value + self::bonus($info) + self::mup($info), 2, '.', '');
                }
                break;
            case 'bonus':
                $info->bonus = $value;
                self::syncBalance($info);
                break;
            case 'mup':
                $info->mup = $value;
                self::syncBalance($info);
                break;
            case 'credit':
                $info->awaiting_deposit = $value;
                break;
        }
    }

    /**
     * Apply an admin wallet credit (add amount); balance syncs after change.
     */
    public static function applyCredit(InfoTradeUser $info, string $normalizedType, float $amount, int $status = 1): string
    {
        switch ($normalizedType) {
            case 'deposit':
                if ($status === 1) {
                    if (self::hasRealDepositColumn()) {
                        $info->real_deposit = self::realDeposit($info) + $amount;
                    } else {
                        $info->balance = number_format((float) ($info->balance ?? 0) + $amount, 2, '.', '');
                    }
                    self::syncBalance($info);
                } else {
                    $info->awaiting_deposit = self::credit($info) + $amount;
                }
                break;
            case 'credit':
                $info->awaiting_deposit = self::credit($info) + $amount;
                break;
            case 'bonus':
                $info->bonus = self::bonus($info) + $amount;
                self::syncBalance($info);
                break;
            case 'mup':
                self::addMup($info, $amount);
                break;
        }

        return $normalizedType;
    }

    /**
     * @param  bool  $reconcileLegacyDrift  When false, admin-set components win (no balance-column override).
     */
    public static function breakdown(InfoTradeUser $info, bool $reconcileLegacyDrift = true): array
    {
        if ($reconcileLegacyDrift) {
            self::reconcileDrift($info, $info->exists && (bool) $info->getKey());
        }

        $real = self::realDeposit($info);
        $credit = self::credit($info);
        $bonus = self::bonus($info);
        $mup = self::mup($info);
        $main = $reconcileLegacyDrift
            ? self::mainBalance($info)
            : round(self::componentSum($info), 2);

        return [
            'real_deposit' => $real,
            'credit' => $credit,
            'bonus' => $bonus,
            'mup' => $mup,
            'main_balance' => $main,
            'balance' => $main,
            'awaiting_deposit' => $credit,
            'total' => $main,
            'total_all_wallets' => $main + $credit,
        ];
    }

    public static function displayTypeLabel(string $normalizedType): string
    {
        return match ($normalizedType) {
            'deposit' => 'Deposit',
            'credit' => 'Credit',
            'bonus' => 'Bonus',
            'mup' => 'MUP',
            default => ucfirst($normalizedType),
        };
    }
}
