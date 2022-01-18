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
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->exec('php artisan DB:seed --class=WeatherSeeder')
        ->hourly();
        $schedule->exec('php artisan DB:seed --class=AqiSeeder')->hourly();
        $schedule->command('strava:refreashToken')->everyThreeHours();
        $schedule->command('strava:activities')->daily();
        $schedule->command('weather:clear')->daily();
        $schedule->exec('php artisan DB:seed --class=EventSeeder')
        ->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
