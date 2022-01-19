<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SlackLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test slack logging is work';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::stack(['weather', 'slack'])->critical('Laraval weather log testing connect to slack');
        Log::stack(['login', 'slack'])->critical('Laraval login log testing connect to slack');
        Log::stack(['strava', 'slack'])->critical('Laraval strava log testing connect to slack');
        Log::stack(['event', 'slack'])->critical('Laraval event log testing connect to slack');
        Log::stack(['controller', 'slack'])->critical('Laraval controller log testing connect to slack');
        Log::stack(['activities', 'slack'])->critical('Laraval activities log testing connect to slack');
        Log::stack(['aqi', 'slack'])->critical('Laraval aqi log testing connect to slack');
        return 0;
    }
}
