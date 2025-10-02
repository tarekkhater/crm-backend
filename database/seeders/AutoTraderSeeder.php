<?php

namespace Database\Seeders;

use App\Models\AutoTrader;
use Illuminate\Database\Seeder;

class AutoTraderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Johnson Dehga','fee' => 100, 'profit' => 40, 'description' => 'Make over 40% profit every month'],
            ['name' => 'Job Actor','fee' => 200, 'profit' => 50, 'description' => 'Make over 60% profit every month'],
            ['name' => 'Philips Anndrew','fee' => 300, 'profit' => 90, 'description' => 'Make over 100% profit every month'],
            ];


        foreach ($items as $item) {
            if (AutoTrader::where('name', '=', $item['name'])->first() === null) {
                AutoTrader::create([
                    'name' => $item['name'],
                    'fee' => $item['fee'],
                    'profit' => $item['profit'],
                    'info' => $item['description'],
                ]);
            }
        }
    }
}
