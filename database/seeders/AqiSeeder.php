<?php

namespace Database\Seeders;

use App\Models\Aqi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AqiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Aqi::class)->truncate();
        $response = Http::get('https://data.epa.gov.tw/api/v1/aqx_p_432?limit=1000&api_key=9be7b239-557b-4c10-9775-78cadfc555e9&sort=ImportDate%20desc&format=json');
        $datas = $response->json();
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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
