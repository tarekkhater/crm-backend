<?php

namespace App\Console\Commands;

use App\Models\AutoProfitLoss;
use App\Models\AutoProfitLossDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoPNL extends Command
{

    protected $signature = 'autopnl:cron';

    protected $description = 'Auto PNL CRON';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \Log::info("Auto PNL Cron is working fine!");
        $autoProfits = AutoProfitLoss::whereStatus(1)->whereStatus(1)->get();
        foreach ($autoProfits as $item){
            if($item->interval_type == 'hour'){
                $last_updated = Carbon::createFromFormat('Y-m-d H:s:i', $item->last_updated)->addHours($item->inter);;
            }else{
                $last_updated = Carbon::createFromFormat('Y-m-d H:s:i', $item->last_updated)->addDays($item->inter);;
            }
            if($item->last_updated < $last_updated){

                $user = User::findOrFail($item->user_id);
                $old_pnl = $user->userInfo->pnl;
                $item->last_updated = Carbon::now();

                if($item->fixed == 'fixed'){
                    $user->userInfo->pnl = $user->userInfo->pnl + $item->amount;
                    $amt = $item->amount;
                    $user->save();
                }else{
                    $amt = ($user->userInfo->balance * $item->amount) / 100;
                    $user->userInfo->pnl = $user->userInfo->pnl + $amt;
                }

                $this->autoPNLActivities($item, $user->userInfo->pnl, $old_pnl, $amt);

                $item->save();
            }

            $item->save();
        }
        $this->info('Successfully runned Auto PNL.');
    }

    public function autoPNLActivities($data, $bal, $old_pnl, $amt){
        $autopnl_activity = new AutoProfitLossDetail();
        $autopnl_activity->balance_before = $old_pnl;
        $autopnl_activity->balance_after = $bal;
        $autopnl_activity->amount = $amt;
        $autopnl_activity->auto_profit_id = $data->id;
        $autopnl_activity->user_id = $data->user_id;
        $autopnl_activity->save();

    }

}
