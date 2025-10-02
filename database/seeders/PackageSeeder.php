<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
        ['name' => 'Bronze','percent_profit' => 10,'period' => '2','min_unit' => 1, 'max_unit' => 3,'price' => 500],
        ['name' => 'Silver','percent_profit' => 30,'period' => '3','min_unit' => 1, 'max_unit' => 5, 'price' => 1000],
        ['name' => 'Gold','percent_profit' => 50,'period' => '4','min_unit' => 1, 'max_unit' => 7, 'price' => 1500],
            ];

        $description = '';

        foreach ($packages as $item) {
            if (Package::where('name', '=', $item['name'])->first() === null) {
                Package::create([
                    'name' => $item['name'],
                    'percent_profit'  => $item['percent_profit'],
                    'period' => $item['period'],
                    'price' => $item['price'],
                    'description' => $description,
                    'min_unit' => $item['min_unit'],
                    'max_unit' => $item['max_unit'],
                    'status' => 1,
                ]);
            }
        }
    }
}
