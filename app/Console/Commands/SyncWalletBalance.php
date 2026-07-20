<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\InfoTradeUser;
use App\Services\Users\UserWalletService;

class SyncWalletBalance extends Command
{
    protected $signature = 'wallet:sync-balance
                            {--user= : Sync a single user_id only}
                            {--dry-run : Preview changes without saving}
                            {--chunk=200 : Records per batch}';

    protected $description = 'Sync balance column = real_deposit + bonus + mup + credit (awaiting_deposit) for existing records';

    public function handle(): int
    {
        if (! Schema::hasColumn('info_trade_users', 'real_deposit')) {
            $this->error('Column real_deposit does not exist. Run migrations first.');
            return self::FAILURE;
        }

        $dryRun  = (bool) $this->option('dry-run');
        $chunk   = (int)  $this->option('chunk');
        $userId  = $this->option('user');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be saved.');
        }

        $query = DB::table('info_trade_users')
            ->whereNotNull('real_deposit')
            ->select('id', 'user_id', 'real_deposit', 'bonus', 'mup', 'awaiting_deposit', 'balance');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $total   = $query->count();
        $updated = 0;
        $skipped = 0;

        $this->info("Found {$total} records to process.");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->orderBy('id')->chunk($chunk, function ($rows) use ($dryRun, &$updated, &$skipped, $bar) {
            foreach ($rows as $row) {
                $real    = (float) ($row->real_deposit   ?? 0);
                $bonus   = (float) ($row->bonus          ?? 0);
                $mup     = (float) ($row->mup            ?? 0);
                $credit  = (float) ($row->awaiting_deposit ?? 0);

                $newBalance = round($real + $bonus + $mup + $credit, 2);
                $oldBalance = round((float) ($row->balance ?? 0), 2);

                if (abs($newBalance - $oldBalance) < 0.005) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                if (! $dryRun) {
                    DB::table('info_trade_users')
                        ->where('id', $row->id)
                        ->update(['balance' => number_format($newBalance, 2, '.', '')]);
                } else {
                    $this->newLine();
                    $this->line(
                        "  user_id={$row->user_id} | old={$oldBalance} → new={$newBalance}"
                        . " (real={$real} bonus={$bonus} mup={$mup} credit={$credit})"
                    );
                }

                $updated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $label = $dryRun ? 'Would update' : 'Updated';
        $this->info("{$label}: {$updated} records.");
        $this->line("Already correct (skipped): {$skipped} records.");

        return self::SUCCESS;
    }
}
