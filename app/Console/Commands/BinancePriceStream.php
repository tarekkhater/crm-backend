<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use WebSocket\Client;
use App\Events\CurrencyRateUpdated;

class BinanceStreamCommand extends Command
{
    protected $signature = 'binance:stream {symbols*}';
    protected $description = 'Stream Binance prices for multiple symbols';

    public function handle()
    {
        $symbols = $this->argument('symbols');
        $streams = implode('/', array_map(fn($s) => strtolower($s).'@trade', $symbols));
        $url = "wss://stream.binance.com:9443/stream?streams={$streams}";

        $this->info("Connecting to Binance WebSocket for: " . implode(', ', $symbols));

        $client = new Client($url);

        while (true) {
            $message = $client->receive();
            $data = json_decode($message, true);

            if (isset($data['stream'], $data['data']['p'])) {
                $symbol = strtoupper(str_replace('@TRADE', '', strtoupper($data['stream'])));
                $price  = $data['data']['p'];

                $this->line("{$symbol} => {$price}");

                // تبعت broadcast
                broadcast(new CurrencyRateUpdated([
                    'symbol' => $symbol,
                    'price'  => $price,
                ]));
            }
        }
    }
}

