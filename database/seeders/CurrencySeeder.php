<?php

namespace Database\Seeders;

use App\Models\CurrencyPair;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            ['sym' => 'BTC', 'ex_sym' => 'BITSTAMP:BTCUSD','image' => '/crypto/btc.svg','name'=> 'Bitcoin'],
            ['sym' => 'ETH', 'ex_sym' => 'COINBASE:ETHUSD','image' => '/crypto/eth.svg','name'=> 'Ethereum'],
            ['sym' => 'XRP', 'ex_sym' => 'BITSTAMP:XRPUSD','image' => '/crypto/xrp.svg','name'=> 'Ripple'],
//            ['sym' => 'ADA','image' => '/crypto/ada.svg','name'=> 'Cardano'],
            ['sym' => 'LTC', 'ex_sym' => 'COINBASE:LTCUSD','image' => '/crypto/ltc.svg','name'=> 'Litecoin'],
//            ['sym' => 'XEM','image' => '/crypto/xem.svg','name'=> 'NEM'],
            ['sym' => 'XLM', 'ex_sym' => 'COINBASE:XLMUSD','image' => '/crypto/xlm.svg','name'=> 'Steller'],
//            ['sym' => 'BNB','image' => '/crypto/bnb.svg','name'=> 'Binance Coin'],
//            ['sym' => 'USDT','image' => '/crypto/usdt.svg','name'=> 'Tether'],
//            ['sym' => 'ETC','image' => '/crypto/etc.svg','name'=> 'Ethereum Classic'],
//            ['sym' => 'DOGE','image' => '/crypto/doge.svg','name'=> 'Dogecoin'],
//            ['sym' => 'BCH','image' => '/crypto/bch.svg','name'=> 'Bitcoin Cash'],
//            ['sym' => 'VEN','image' => '/crypto/ven.svg','name'=> 'VeChain'],
        ];

        foreach ($items as $item) {
            if (CurrencyPair::where('sym', '=', $item['sym'])->first() === null) {
                CurrencyPair::create([
                    'name' => $item['name'],
                    'sym' => $item['sym'],
                    'ex_sym' => $item['ex_sym'],
                    'image' => $item['image'],
                ]);
            }
        }
    }
}
