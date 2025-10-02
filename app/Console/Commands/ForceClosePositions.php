<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use DB;

class ForceClosePositions extends Command
{
    protected $signature = 'positions:force-close';
    protected $description = 'Force close positions if profit equals user balance';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \Log::info("Checking positions for force close...");

        $user_ids = Position::whereNull('close_at')->groupBy('user_id')->pluck('user_id');
        $users = User::whereIn('id', $user_ids)->get();

        $closedUsers = [];

        foreach ($users as $user) {
            $openPositions = Position::where('user_id', $user->id)->whereNull('close_at')->get();
            $totalProfit = $openPositions->sum('net_profit');
            $balance = $user->userInfo->balance;
            
            // $pnl = Position::where('close_at',null)->whereUserId($user->id)->sum('net_profit');
            // $profit = $pnl;
            // $bal = $this->user->userInfo->balance;
           
            
            
            // // inputs
            // $balance = $this->user->userInfo->balance;       // الرصيد الأساسي
            // $pnl     = $totalProfit;       // الربح/الخسارة العائم
            // $marginUsed = $openPositions->sum('trade_amount');     // الهامش المستخدم
            
            // // calculations
            // $equity = $balance + $pnl;          // Equity
            // $freeMargin = $equity - $marginUsed; // Free Margin

    //   \Log::Info($totalProfit.'---'.$user->id);
            $free = $balance + $totalProfit;

            if ($free <= 0) {
                foreach ($openPositions as $position) {
                    $position->close_at = Carbon::now();
                    $position->save();
                }
                $user->userInfo->balance = 0;
                $user->userInfo->save();
                $closedUsers[] = $user->id;
                \Log::info("Closed positions for user #{$user->id} due to PnL = balance.");
            }
        }

        $this->info("Closed positions for users: " . implode(', ', $closedUsers));

        return 0;
    }
}
