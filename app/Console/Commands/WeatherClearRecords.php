<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\WeatherData;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeatherClearRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old weather records';

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
        try {

            $lastDay = Carbon::today('Asia/Taipei')->subDays(1);

            app(WeatherData::class)
                ->where('start_time', '<=', $lastDay)
                ->where('end_time', '<=', $lastDay)
                ->delete();

            Log::info('天氣舊資料刪除完成');
        } catch (Throwable $e) {
            Log::info($e);
        }

        return 0;
    }
}
