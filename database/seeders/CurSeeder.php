<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyPair;
use Illuminate\Database\Seeder;

class CurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['sign' => '$', 'code' => 'USD','name'=> 'Dollar'],
            ['sign' => '£', 'code' => 'GBP','name'=> 'Pound'],
            ['sign' => '€', 'code' => 'EUR','name'=> 'Euro'],
            ];

        foreach ($items as $item) {
            if (Currency::where('code', '=', $item['code'])->first() === null) {
                Currency::create([
                    'name' => $item['name'],
                    'sign' => $item['sign'],
                    'code' => $item['code']
                ]);
            }
        }
    }
}
