<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\CurrencyPair;
use App\Oanda;

class CurrencyRateService
{
    public function getAllCurrencyRates()
    {
      
        $currencies = CurrencyPair::where('disabled', false)
            ->select(['id', 'sym','ex_sym','base', 'type', 'buy_spread', 'sell_spread'])
            ->get();

        $rates = [];

        $groupedCurrencies = $currencies->groupBy('type');

        // تجميع جميع العملات في طلبات bulk
        if (isset($groupedCurrencies['crypto'])) {
            $rates = array_merge($rates, $this->getBulkCryptoRates($groupedCurrencies['crypto']));
        }

        if (isset($groupedCurrencies['forex'])) {
            $rates = array_merge($rates, $this->getBulkForexRates($groupedCurrencies['forex']));
        }

        if (isset($groupedCurrencies['stocks'])) {
            $rates = array_merge($rates, $this->getBulkStocksRates($groupedCurrencies['stocks']));
        }

        // استخدام Oanda bulk للـ indices والـ commodities
        if (isset($groupedCurrencies['indices'])) {
            $rates = array_merge($rates, $this->getBulkIndicesRates($groupedCurrencies['indices']));
        }

        if (isset($groupedCurrencies['commodities'])) {
            $rates = array_merge($rates, $this->getBulkCommoditiesRates($groupedCurrencies['commodities']));
        }

        return $rates;
    }

    // ================= CRYPTO =================
    private function getBulkCryptoRates($currencies)
    {
        $rates = [];

        try {
            $symbols = [];
            foreach ($currencies as $currency) {
                $base = strtoupper($currency->base) === 'USD' ? 'USDT' : strtoupper($currency->base);
                $symbols[] = strtoupper($currency->sym) . $base;
            }

            $chunks = array_chunk($symbols, 150);
            foreach ($chunks as $chunk) {
                $url = 'https://api.binance.com/api/v3/ticker/price?symbols=' . urlencode(json_encode($chunk));
                $response = Http::timeout(1)->get($url);
                $data = $response->json();

                $priceData = [];
                foreach ($data as $item) {
                    $priceData[$item['symbol']] = $item['price'];
                }

                foreach ($currencies as $currency) {
                    $base = strtoupper($currency->base) === 'USD' ? 'USDT' : strtoupper($currency->base);
                    $symbol = strtoupper($currency->sym) . $base;
                    $rate = floatval($priceData[$symbol] ?? 0);
                    $rates[] = $this->formatRate($currency, $rate);
                }
            }
        } catch (\Exception $e) {
            // skip
        }

        return $rates;
    }

    // ================= FOREX =================
    private function getBulkForexRates($currencies)
    {
        $rates = [];
        try {
            $instruments = [];
            foreach ($currencies as $currency) {
                $instruments[] = $currency->sym . '_' . $currency->base;
            }
            
            $oandaApi = setting('oando_api', '45a68744a7d51608ed4177c4e8d548ad-a396f9135670bd7373b82bb90ed2aea7');
            $oandaAccount = setting('oando_account_id', '101-004-15523510-001');
            
            
            $api = new Oanda($oandaApi, $oandaAccount);
            $res = $api->getPrice(implode(',', $instruments));
            Log::info('Oanda response', $res);

            $priceData = [];
            if (isset($res['prices']) && is_array($res['prices'])) {
                foreach ($res['prices'] as $item) {
                    $priceData[$item['instrument']] = $item['closeoutAsk'] ?? 0;
                }
            }


            foreach ($currencies as $currency) {
                $key = $currency->sym . '_' . $currency->base;
                $rate = floatval($priceData[$key] ?? 0);
                $rates[] = $this->formatRate($currency, $rate);
            }
        } catch (\Exception $e) {
            // skip
        }
        return $rates;
    }

    // ================= STOCKS =================
    // ================= STOCKS =================
private function getBulkStocksRates($currencies)
{
    $rates = [];

    try {
        $lastFetch = cache('stocks_last_fetch', 0);
        $now = time();

        if ($now - $lastFetch < 15) {
            foreach ($currencies as $currency) {
                $rate = cache("stock_price_{$currency->sym}", 0);
                $rates[] = $this->formatRate($currency, $rate);
            }
            Log::info("STOCKS RATES: " . json_encode($rates));
            return $rates;
        }

        $symbols = $currencies->pluck('sym')->toArray();
        $priceData = [];

        $chunks = array_chunk($symbols, 21);

        $responses = Http::pool(function ($pool) use ($chunks) {
            foreach ($chunks as $chunk) {
                foreach ($chunk as $symbol) {
                    $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1m";
                    $pool->timeout(15)->get($url);
                }
            }
        });
        Log::info("STOCKS responses: " . json_encode($responses));
        $responseIndex = 0;
        foreach ($chunks as $chunk) {
            foreach ($chunk as $symbol) {
                $response = $responses[$responseIndex];
                $responseIndex++;

                try {
                    if ($response instanceof \Exception || !$response->successful()) {
                        $priceData[$symbol] = cache("stock_price_{$symbol}", 0);
                        continue;
                    }

                    $data = $response->json();
                    if (isset($data['chart']['result'][0]['meta']['regularMarketPrice'])) {
                        $price = $data['chart']['result'][0]['meta']['regularMarketPrice'];
                        $priceData[$symbol] = $price;
                        cache(["stock_price_{$symbol}" => $price], now()->addMinutes(2));
                    } else {
                        $priceData[$symbol] = cache("stock_price_{$symbol}", 0);
                    }
                } catch (\Exception $e) {
                    $priceData[$symbol] = cache("stock_price_{$symbol}", 0);
                }
            }
        }

        cache(['stocks_last_fetch' => $now], now()->addMinutes(2));

        foreach ($currencies as $currency) {
            $rate = floatval($priceData[$currency->sym] ?? 0);
            $rates[] = $this->formatRate($currency, $rate);
        }
    } catch (\Exception $e) {
        foreach ($currencies as $currency) {
            $rate = cache("stock_price_{$currency->sym}", 0);
            $rates[] = $this->formatRate($currency, $rate);
        }
    }
    Log::info("STOCKS RATES: " . json_encode($rates));
    return $rates;
}



    // ================= INDICES =================
    private function getBulkIndicesRates($currencies)
    {
        $rates = [];
        try {
            // تجميع جميع الرموز في قائمة واحدة
            $instruments = [];
            foreach ($currencies as $currency) {
                $instruments[] = $currency->sym . '_' . $currency->base;
            }
            
            $oandaApi = setting('oando_api', '45a68744a7d51608ed4177c4e8d548ad-a396f9135670bd7373b82bb90ed2aea7');
            $oandaAccount = setting('oando_account_id', '101-004-15523510-001');
            
            
            $api = new Oanda($oandaApi, $oandaAccount);
            $res = $api->getPrice(implode(',', $instruments));

            $priceData = [];
            if (isset($res['prices']) && is_array($res['prices'])) {
                foreach ($res['prices'] as $item) {
                    $priceData[$item['instrument']] = $item['closeoutAsk'] ?? 0;
                }
            }


            foreach ($currencies as $currency) {
                $key = $currency->sym . '_' . $currency->base;
                $rate = floatval($priceData[$key] ?? 0);
                $rates[] = $this->formatRate($currency, $rate);
            }
        } catch (\Exception $e) {
        }

        return $rates;
    }

    // ================= COMMODITIES =================
    private function getBulkCommoditiesRates($currencies)
    {
        $rates = [];
        try {
            // تجميع جميع الرموز في قائمة واحدة
            $instruments = [];
            foreach ($currencies as $currency) {
                $instruments[] = $currency->sym . '_' . $currency->base;
            }
            
            $oandaApi = setting('oando_api', '45a68744a7d51608ed4177c4e8d548ad-a396f9135670bd7373b82bb90ed2aea7');
            $oandaAccount = setting('oando_account_id', '101-004-15523510-001');
            

            $api = new Oanda($oandaApi, $oandaAccount);
            $res = $api->getPrice(implode(',', $instruments));


            $priceData = [];
            if (isset($res['prices']) && is_array($res['prices'])) {
                foreach ($res['prices'] as $item) {
                    $priceData[$item['instrument']] = $item['closeoutAsk'] ?? 0;
                }
            }


            foreach ($currencies as $currency) {
                $key = $currency->sym . '_' . $currency->base;
                $rate = floatval($priceData[$key] ?? 0);
                $rates[] = $this->formatRate($currency, $rate);
            }
        } catch (\Exception $e) {
        }

        return $rates;
    }

    // ================= Helpers =================
    private function formatRate($currency, $rate)
    {
        return [
            'id'   => $currency->id,
            'sym'  => $currency->sym,
            'name'=>$currency->ex_sym,
            'leverage'=>$currency->leverage,
            "buy_spread"=> $currency->buy_spread,
            "sell_spread"=> $currency->sell_spread,
            'current_price' => round($rate,5),
            'rate' => round($rate,5),
            'buy_p'  => $this->calculateBuyPrice($rate, $currency->buy_spread),
            'sell_p' => $this->calculateSellPrice($rate, $currency->sell_spread),
            'amount'=>$currency->amount,
        ];
    }
    
     public function calculateBuyPrice($currentPrice,$sell_spreads){
       
    if((float) $sell_spreads > 0){
        $sell_spread = floatval(($sell_spreads * $currentPrice) / 100);
        $s_price = floatval($currentPrice) - floatval($sell_spread);
        return $this->truncate_number($s_price, 4);
    }
     return $this->truncate_number($currentPrice, 4);
    }

     public function calculateSellPrice($currentPrice,$buy_spreads){
        if((float) $buy_spreads > 0){
            $buy_spread = floatval(($buy_spreads * $currentPrice) / 100);
            $b_price = floatval($currentPrice) + floatval($buy_spread);
            return $this->truncate_number($b_price, 4);
        }
        return $this->truncate_number($currentPrice, 4);
        
    }
    
    function truncate_number($number, $decimals = 2) {
        
        $factor = pow(10, $decimals);
        return floor($number * $factor) / $factor;
    }
    

    // private function calculateBuyPrice($rate, $spread)
    // {
    //     return round($rate + ($rate * $spread / 100), 5);
    // }

    // private function calculateSellPrice($rate, $spread)
    // {
    //     return round($rate - ($rate * $spread / 100), 5);
    // }
}
