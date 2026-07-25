<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncWalletBalance extends Command
{
    protected $signature = 'wallet:sync-balance
                            {--user=    : Sync a single user_id only}
                            {--dry-run  : Preview changes without saving}
                            {--no-safe  : Allow balance to decrease (default: only increase)}
                            {--chunk=200: Records per batch}';

    protected $description = 'Sync balance = real_deposit + bonus + mup + credit for existing records (safe: only increases balance by default)';

    public function handle(): int
    {
        if (! Schema::hasColumn('info_trade_users', 'real_deposit')) {
            $this->error('Column real_deposit does not exist. Run migrations first.');
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $noSafe = (bool) $this->option('no-safe');
        $chunk  = (int)  $this->option('chunk');
        $userId = $this->option('user');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be saved.');
        }
        if (! $noSafe) {
            $this->info('SAFE MODE — will only increase balance, never decrease.');
            $this->line('  Use --no-safe to also allow decreasing balance.');
        } else {
            $this->warn('UNSAFE MODE — balance may be decreased to match components.');
        }

        $query = DB::table('info_trade_users')
            ->whereNotNull('real_deposit')
            ->select('id', 'user_id', 'real_deposit', 'bonus', 'mup', 'awaiting_deposit', 'balance');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $total       = $query->count();
        $updated     = 0;
        $skipped     = 0;
        $flaggedRows = [];

        $this->info("Found {$total} records to process.");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->orderBy('id')->chunk($chunk, function ($rows) use (
            $dryRun, $noSafe, &$updated, &$skipped, &$flaggedRows, $bar
        ) {
            foreach ($rows as $row) {
                $real   = round((float) ($row->real_deposit     ?? 0), 2);
                $bonus  = round((float) ($row->bonus            ?? 0), 2);
                $mup    = round((float) ($row->mup              ?? 0), 2);
                $credit = round((float) ($row->awaiting_deposit ?? 0), 2);
                $old    = round((float) ($row->balance          ?? 0), 2);
                $new    = round($real + $bonus + $mup + $credit, 2);
                $diff   = $new - $old;

                // Already in sync
                if (abs($diff) < 0.005) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // balance > components: could mean balance has valid trading history — skip unless --no-safe
                if ($diff < 0 && ! $noSafe) {
                    $flaggedRows[] = [
                        'user_id' => $row->user_id,
                        'old'     => $old,
                        'new'     => $new,
                        'diff'    => round($diff, 2),
                    ];
                    $bar->advance();
                    continue;
                }

                if (! $dryRun) {
                    DB::table('info_trade_users')
                        ->where('id', $row->id)
                        ->update(['balance' => number_format($new, 2, '.', '')]);
                } else {
                    $symbol = $diff > 0 ? '+' : '';
                    $this->newLine();
                    $this->line(
                        "  user_id={$row->user_id} | {$old} → {$new} ({$symbol}" . round($diff, 2) . ")"
                        . "  [real={$real} bonus={$bonus} mup={$mup} credit={$credit}]"
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

        if (count($flaggedRows) > 0) {
            $this->newLine();
            $this->warn('FLAGGED (balance > components — skipped, review manually): ' . count($flaggedRows));
            foreach ($flaggedRows as $r) {
                $this->line(
                    "  user_id={$r['user_id']}  balance={$r['old']}  components={$r['new']}  diff={$r['diff']}"
                );
            }
            $this->newLine();
            $this->warn('If these are intentional, run again with --no-safe to apply.');
        }

        return self::SUCCESS;
    }
}
