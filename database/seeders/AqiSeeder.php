<?php

namespace Database\Seeders;

use App\Models\Aqi;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

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

        try {
            app(Aqi::class)->truncate();
            $response = Http::get('https://data.epa.gov.tw/api/v1/aqx_p_432?limit=1000&api_key=9be7b239-557b-4c10-9775-78cadfc555e9&sort=ImportDate%20desc&format=json');
            $datas = $response->json();
            foreach($datas['records'] as $data) {
                $aqi = app(Aqi::class)->where('SiteName', $data['SiteName'])->first();
                foreach($data as $key => $value) {
                    if ($key === 'PM2.5') {
                        $key = 'PM2_5';
                    }
                    if($key === 'PM2.5_AVG') {
                        $key = 'PM2_5_AVG';
                    }
                    if ($key === 'County') {
                        $key = 'city_id';
                        $city = app(City::class)->where('city_name', $value)->first();
                        $value = $city->id;
                    }
                    $arr[$key] = $value;
                }
                if (!$aqi) {
                    $arr['id'] = uniqid();
                    app(Aqi::class)->create($arr);
                } else {
                    $aqi->update($arr);
                }
            }
            Log::info('空氣品質資料更新完成');
        } catch (Throwable $e) {
            Log::info($e);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
