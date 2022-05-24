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
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('DB:seed --class=WeatherSeeder --force')
        ->hourly();
        $schedule->command('DB:seed --class=AqiSeeder --force')->hourly();
        $schedule->command('strava:refreashToken')->everyThreeHours();
        $schedule->command('strava:activities')->dailyAt('00:00');
        $schedule->command('weather:clear')->dailyAt('23:30');
        $schedule->command('events:send')->dailyAt('09:00');
        $schedule->command('DB:seed --class=EventSeeder --force')
        ->dailyAt('01:00');
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
