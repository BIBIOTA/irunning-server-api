<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WeatherData;
use App\Jobs\SendEmail;
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

            logger($lastDay);

            app(WeatherData::class)
                ->whereDate('start_time', '<=', $lastDay)
                ->whereDate('end_time', '<=', $lastDay)
                ->delete();

            Log::channel('weather')->info('天氣舊資料刪除完成');
        } catch (Throwable $e) {
            Log::stack(['weather', 'slack'])->error($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'weather clear error log', 'main' => $e]);
        }

        return 0;
    }
}
