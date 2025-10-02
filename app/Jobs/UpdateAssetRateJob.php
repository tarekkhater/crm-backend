<?php

namespace App\Jobs;

use App\Events\CurrencyRateUpdated;
use App\Models\CurrencyPair;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Controller;

class UpdateAssetRateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $controller = new Controller();
        $currencies = CurrencyPair::all()->chunk(10); // تحديث 20 عملة في كل دفعة

        foreach ($currencies as $chunk) {
            $broadcastData = [];

            foreach ($chunk as $currency) {
                $rate = $controller->getCurRate($currency->sym, $currency->base, $currency->type);

                if ($rate > 0) {
                    $broadcastData[] = [
                        'id' => $currency->id,
                        'sym' => $currency->sym,
                        'base' => $currency->base,
                        'type' => $currency->type,
                        'rate' => $rate,
                    ];
                }
            }

            if (!empty($broadcastData)) {
                broadcast(new CurrencyRateUpdated($broadcastData));
            }

            usleep(50000); // 0.05 ثانية بين كل chunk لتخفيف الضغط
        }
    }
}
