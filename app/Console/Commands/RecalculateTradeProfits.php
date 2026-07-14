<?php

namespace App\Console\Commands;

use App\Models\Position;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecalculateTradeProfits extends Command
{
    protected $signature = 'trades:recalculate-profit';
    protected $description = 'Recalculate open position PnL and auto-close on SL/TP';

    public function handle()
    {
        $positions = Position::query()
            ->whereNull('close_at')
            ->with(['currency', 'user.userInfo'])
            ->get();

        $closed = 0;

        foreach ($positions as $position) {
            if (!$position->currency) {
                continue;
            }

            $profit = $position->calculateFloatingProfit();
            $position->profit = $profit;
            $position->net_profit = $profit;
            $position->live_loss = $profit < 0 ? abs($profit) : 0;

            if ($position->checkAndCloseByPnL($profit)) {
                $closed++;
                continue;
            }

            $position->saveQuietly();
        }

        Log::info("trades:recalculate-profit processed {$positions->count()} open positions, closed {$closed}.");

        return 0;
    }
}
