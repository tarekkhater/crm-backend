<?php
 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Position;
use App\Services\TradeService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RecalculateTradeProfits extends Command
{
    protected $signature = 'trades:recalculate-profit';
    protected $description = 'Recalculate profit for all trades based on current price and amount';
    
    public function handle()
    {
            // $this->CalcProfitt();
            Log::info("✅ Profit recalculation complete. Sleeping 1 second...");
           
        return 0;
    }
    
    private function CalcProfitt(){
         $trades = Position::where('close_at',null)->get();
         $controller = new Controller();
        
        
           
        foreach ($trades as $trade){
            if (
            !$trade->currency ||
            !$trade->opening_price ||
            !$trade->amount ||
            !$trade->direction ||
            !$trade->leverage
        ) {
            return;
        }
        if($trade->close_at == null){
        // $rate = $controller->getCurRate($trade->currency->sym, $trade->currency->base, $trade->currency->type);
        // $trade->currency->rate = $rate;
        // $currentPrice = $rate;
        // $trade->currency->save();
        
       
        // $spread = $trade->spread ?? 0;
    
        // الهامش
        // $trade->margin = round($trade->amount / $trade->leverage, 2);
    
        // تكلفة السبريد
        // $spreadCost = $spread * 0.01 * $trade->amount;
    
        // // الربح
        // $direction = strtolower($trade->direction);
        // if ($direction === 'buy') {
        //     $priceDiff = round($currentPrice, 2) - round($trade->opening_price, 2);
        // } elseif ($direction === 'sell') {
        //     $priceDiff = round($trade->opening_price, 2) - round($currentPrice, 2);
        // } else {
        //     $priceDiff = 0;
        // }
    $priceDiff = 0;
       if($direction === 'buy'){

                    $priceDiff = (float)$trade->currency->rate - $trade->opening_price;
                    
                    
                    // الربح الخام
                    
                    
            }else{
                    
                 
                   
                    $priceDiff = $trade->opening_price - (float)(float)$trade->currency->rate;
                    
                    
                    // الربح الخام
                    // $rawProfit = $priceDiff * $trade->lot * $trade->amount;
                    
                    // // تكلفة السبريد (لو في ربح موجب فقط)
                    // $spreadCost = $priceDiff > 0 ? ($trade->spread / 100) * $trade->lot * $trade->amount : 0;
                    
                    // // صافي الربح
                    // $profit = $rawProfit - $spreadCost + $trade->com;
                    
                    // // تنسيق الرقم لو محتاج (اختياري)
                    // $profit =$profit;
                    // $trade->profit = $profit;
                    // $trade->net_profit = $profit;
                    
            }
    
        $rawProfit = $priceDiff * $trade->lot * $trade->amount;
        // تكلفة السبريد (لو في ربح موجب فقط)
        $spreadCost = $priceDiff > 0 ? ($trade->spread / 100) * $trade->lot * $trade->amount : 0;
        // صافي الربح
        $profit = $rawProfit - $spreadCost + $trade->com;
        $trade->profit = $profit;
        $trade->net_profit = $profit;
        // الخسارة المباشرة
        $trade->live_loss = $trade->profit < 0 ? abs($trade->profit) : 0;
    
        // قيمة الصفقة
        // $trade->trade_amount = round($trade->amount - $spreadCost, 2);
        
            $trade->save();
        }
        
        }
    }
}

