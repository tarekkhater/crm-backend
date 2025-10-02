<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TradesController;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\Position;
class LiquidateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liquidate:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        \Log::info("Liquidate Cron is working fine!");
        
       
            // $tradeCon = new TradesController();
            // $all_active_trades = Trade::whereStatus(0)->get();
            // foreach ($all_active_trades as $trade){
            //     $tradeCon->updateTradeProfit($trade->id);
            // }
            // $user_ids = Trade::whereStatus(0)->groupBy('user_id')->pluck('user_id');
            // $activeUsers = User::whereIn('id',$user_ids)->get();
           
        
        // foreach ($activeUsers as $user){
        //     $pnl = Trade::whereStatus(0)->whereUserId($user->id)->sum('profit');

        //     $total_trade = Trade::whereStatus(0)->whereUserId($user->id)->sum('traded_amount');
        //     $bal = $user->userInfo->balance;
        //     $freeMargin = ($bal - $total_trade) + $pnl;

        //     $balPercentage = ($bal * setting('liquidation_percent', 10)) / 100;
        //     if($freeMargin < $balPercentage){
        //         $tradeCon->closeAllUserTrades($user->id);
        //     }
        // }
        // return count($all_active_trades);
    }
    
    
    


    private function updateTradeProfit($id){
        
         $trade = Trade::findOrFail($id);
        $rate = optional($trade->currency)->rate;
        $con = new Controller();
        $tradeController = new TradesController();
        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base,$trade->currency->type);
        if($coinPrice === 0){
            $coinPrice = $rate;
        }
        $old_coin_value = $trade->amount / $trade->opening_price;
        $trade->closing_price =  $coinPrice;
        $cryptoRate = $coinPrice;
        $trade->close_at = null;
        // $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);
        $pl = abs(($coinPrice  - $opening_price) * $qty);
        $trade->profit = $pl;
        
        if($trade->trade_type == 'Buy')
        {
            if($opening_price > $cryptoRate)
            {
                $trade->profit = $pl * (-1);
                $trade->save();
            }
            else if ($opening_price < $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        } else if($trade->trade_type == 'Sell')
        {
            if($opening_price < $cryptoRate)
            {
                $trade->profit = $pl * (-1);
                $trade->save();
            }
            else if($opening_price > $cryptoRate)
            {
                $trade->profit = $pl;
                $trade->save();
            }
        }

        //check profit / loss
        if($trade->is_take_profit){
            if($trade->profit >= $trade->take_profit){
                $tradeController->updateTrade($trade->id);
            }
        }
        if($trade->is_stop_loss){
            if(((-1)*$trade->stop_loss) >= $trade->profit){
                $tradeController->updateTrade($trade->id);
            }
        }
        
        
        // $trade = Trade::where('id', $id)->firstOrFail();
        // $rate = optional($trade->currency)->rate;
        // $con = new Controller();
        // $tradeController = new TradesController();
        // $coinPrice = $con->getCurRate($trade->currency->sym, $trade->currency->base,$trade->currency->type);
        // if($coinPrice === 0){
        //     $coinPrice = $rate;
        // }
        // $old_coin_value = $trade->amount / $trade->opening_price;
        // $trade->closing_price =  $coinPrice;
        // $cryptoRate = $coinPrice;
        // $trade->close_at = null;
        // $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);

        // $trade->profit = $pl;

        // if($trade->trade_type == 'buy')
        // {
        //     if($trade->opening_price > $cryptoRate)
        //     {
        //         $trade->profit = $pl * (-1);
        //         $trade->save();
        //     }
        //     else if ($trade->opening_price < $cryptoRate) {
        //         $trade->profit = $pl;
        //         $trade->save();
        //     }
        // } else if($trade->trade_type == 'sell')
        // {
        //     if($trade->opening_price < $cryptoRate)
        //     {
        //         $trade->profit = $pl * (-1);
        //         $trade->save();
        //     }
        //     else if($trade->opening_price > $cryptoRate)
        //     {
        //         $trade->profit = $pl;
        //         $trade->save();
        //     }
        // }

        // //check profit / loss
        // if($trade->is_take_profit){
        //     if($trade->profit >= $trade->take_profit){
        //         $tradeController->updateTrade($trade->id);
        //     }
        // }
        // if($trade->is_stop_loss){
        //     if(((-1)*$trade->stop_loss) >= $trade->profit){
        //         $tradeController->updateTrade($trade->id);
        //     }
        // }
    }
}
