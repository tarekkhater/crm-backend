<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['autotrader' => 0],
            ['trade' => 1],
            ['invest' => 1],
            ['overnight_com' => 1],
            ['subscription' => false],
            ['mining' => true],
        ];
        foreach ($items as $item){
            setting($item)->save();
        }
    }
}
