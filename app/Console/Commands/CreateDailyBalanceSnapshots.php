<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InfoTradeUser;
use Carbon\Carbon;

class CreateDailyBalanceSnapshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshots:update-yesterday-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update balance_yesterday field for all users with current balance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to update yesterday balance for all users...');
        
        $usersCount = 0;
        
        // جلب كل سجلات info_trade_users وتحديث balance_yesterday بالرصيد الحالي
        InfoTradeUser::chunk(100, function ($userInfos) use (&$usersCount) {
            foreach ($userInfos as $userInfo) {
                // نقل الرصيد الحالي لـ balance_yesterday
                $currentTotalBalance = (float)($userInfo->balance ?? 0) + (float)($userInfo->money ?? 0);
                
                $userInfo->balance_yesterday = $currentTotalBalance;
                $userInfo->save();
                
                $usersCount++;
            }
        });
        
        $this->info("✅ Successfully updated yesterday balance for {$usersCount} users!");
        
        return 0;
    }
}
