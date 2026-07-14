<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\LiquidateUser::class,
        // Commands\CleanExpiredOtps::class,
        // \App\Console\Commands\AssetsUpdate::class,
        //  Commands\RunCurrencyBroadcast::class,
        // \App\Console\Commands\BinanceStreamCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
            $schedule->command('update_assets:cron')->everyMinute();
            $schedule->command('trades:recalculate-profit')->everyMinute();
            
            // تحديث balance_yesterday يومياً - يشتغل كل يوم الساعة 11:59 مساءً
            $schedule->command('snapshots:update-yesterday-balance')->dailyAt('23:59');

        //  $schedule->command('report:send')->everyMinute();
        //  $schedule->command('margin_call:cron')->everyFiveMinutes();
        //  $schedule->command('database:backup')->everySixHours();
        //  $schedule->command('otp:clean-expired')->everyThirtyMinutes(); // You can adjust the frequency

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
