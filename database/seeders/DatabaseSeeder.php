<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(LaratrustSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(AutoTraderSeeder::class);
        $this->call(PlanSeeder::class);
    }
}
