<?php

namespace App\Console\Commands;

use App\Models\Aqi;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AqiSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取得aqi資料';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Aqi::class)->truncate();
        $response = Http::get('https://data.epa.gov.tw/api/v1/aqx_p_432?limit=1000&api_key=9be7b239-557b-4c10-9775-78cadfc555e9&sort=ImportDate%20desc&format=json');
        $datas = $response->json();
        if (count($datas['records']) > 0) {
            foreach($datas['records'] as $data) {
                $arr = [];
                foreach($data as $key => $value) {
                    if ($key === 'PM2.5') {
                        $key = 'PM2-5';
                    } else if($key === 'PM2.5_AVG') {
                        $key = 'PM2-5_AVG';
                    }
                    $arr[$key] = $value;
                }
                app(Aqi::class)->insert($arr);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Log::info('Aqi資料更新完成');
        return 0;
    }
}
