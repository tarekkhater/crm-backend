<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Bronze', 'Silver'];
        $plans = [
            ['color' => '#000000', 'icon' => 'no-image.jpg', 'amount' => '12345', 'status' => true, 'features' => ['Excellent Feature', 'Great Feature']],
            ['color' => '#000000', 'icon' => 'no-image.jpg', 'amount' => '54321', 'status' => true, 'features' => ['Excellent Feature', 'Great Feature']]
        ];

        foreach ($plans as $key => $plan) {
            Plan::updateOrCreate(['name' => $names[$key]], $plan);
        }
    }
}
