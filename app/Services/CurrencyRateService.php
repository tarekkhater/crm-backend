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

        if (isset($groupedCurrencies['stocks'])) {
            $rates = array_merge($rates, $this->getBulkStocksRates($groupedCurrencies['stocks']));
        }

        // Forex + indices + commodities in one Oanda call (live bid/ask mid, not stale closeoutAsk)
        $oandaGroups = [];
        foreach (['forex', 'indices', 'commodities'] as $oandaType) {
            if (isset($groupedCurrencies[$oandaType])) {
                $oandaGroups[$oandaType] = $groupedCurrencies[$oandaType];
            }
        }
        if (!empty($oandaGroups)) {
            $rates = array_merge($rates, $this->getBulkOandaRates($oandaGroups));
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

    // ================= OANDA (forex / indices / commodities) =================
    private function getBulkOandaRates(array $groupedCurrencies): array
    {
        $rates = [];
        $currencyMap = [];

        foreach ($groupedCurrencies as $currencies) {
            foreach ($currencies as $currency) {
                $key = $currency->sym . '_' . $currency->base;
                $currencyMap[$key] = $currency;
            }
        }

        if (empty($currencyMap)) {
            return $rates;
        }

        try {
            $oandaApi = setting('oando_api', '45a68744a7d51608ed4177c4e8d548ad-a396f9135670bd7373b82bb90ed2aea7');
            $oandaAccount = setting('oando_account_id', '101-004-15523510-001');
            $api = new Oanda($oandaApi, $oandaAccount);

            $priceData = [];
            foreach (array_chunk(array_keys($currencyMap), 50) as $instrumentChunk) {
                $res = $api->getPrice(implode(',', $instrumentChunk));
                foreach ($res['prices'] ?? [] as $item) {
                    $priceData[$item['instrument']] = $this->parseOandaMidPrice($item);
                }
            }

            foreach ($currencyMap as $key => $currency) {
                $rate = floatval($priceData[$key] ?? 0);
                $rates[] = $this->formatRate($currency, $rate);
            }
        } catch (\Exception $e) {
            // skip
        }

        return $rates;
    }

    /**
     * Live mid price from bid/ask — closeoutAsk lags on forex ticks.
     */
    private function parseOandaMidPrice(array $item): float
    {
        $bid = isset($item['bids'][0]['price']) ? (float) $item['bids'][0]['price'] : 0;
        $ask = isset($item['asks'][0]['price']) ? (float) $item['asks'][0]['price'] : 0;

        if ($bid > 0 && $ask > 0) {
            return ($bid + $ask) / 2;
        }

        return (float) ($item['closeoutAsk'] ?? $item['closeoutBid'] ?? 0);
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
    return $rates;
}



    // ================= Helpers =================
    private function formatRate($currency, $rate)
    {
        $priceDecimals = $currency->type === 'forex' ? 5 : 4;

        return [
            'id'   => $currency->id,
            'sym'  => $currency->sym,
            'name'=>$currency->ex_sym,
            'type' => $currency->type, // نضيف الـ type
            'leverage'=>$currency->leverage,
            "buy_spread"=> $currency->buy_spread,
            "sell_spread"=> $currency->sell_spread,
            'current_price' => round($rate, 5),
            'rate' => round($rate, 5),
            'buy_p'  => $this->calculateBuyPrice($rate, $currency->buy_spread, $priceDecimals),
            'sell_p' => $this->calculateSellPrice($rate, $currency->sell_spread, $priceDecimals),
            'amount'=>$currency->amount,
        ];
    }
    
     public function calculateBuyPrice($currentPrice, $sell_spreads, $decimals = 4)
    {
        if ((float) $sell_spreads > 0) {
            $sell_spread = floatval(($sell_spreads * $currentPrice) / 100);
            $s_price = floatval($currentPrice) - floatval($sell_spread);
            return $this->truncate_number($s_price, $decimals);
        }
        return $this->truncate_number($currentPrice, $decimals);
    }

    public function calculateSellPrice($currentPrice, $buy_spreads, $decimals = 4)
    {
        if ((float) $buy_spreads > 0) {
            $buy_spread = floatval(($buy_spreads * $currentPrice) / 100);
            $b_price = floatval($currentPrice) + floatval($buy_spread);
            return $this->truncate_number($b_price, $decimals);
        }
        return $this->truncate_number($currentPrice, $decimals);
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
