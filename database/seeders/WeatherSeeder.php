<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Weather;
use App\Models\AT;
use App\Models\CI;
use App\Models\PoP6h;
use App\Models\PoP12h;
use App\Models\RH;
use App\Models\T;
use App\Models\Td;
use App\Models\WD;
use App\Models\WeatherDescription;
use App\Models\WS;
use App\Models\Wx;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class WeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
        
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            app(Weather::class)->truncate();
    
            $datas = app(City::class)::select('id', 'dataid')->whereNotNull('dataid')->get();

            $weathersColumns = [
                'CI' => 'ci', 
                'T' => 'temperature', 
                'AT' => 'apparent_temperature', 
                'PoP6h' => 'pop6h', 
                'Wx' => 'wx', 
            ];

            // TODO 鄉鎮區取得方法整合
            foreach($datas as $city) {
                $districts = app(District::class)->where('city_id', $city->id)->get();
                foreach($districts as $district) {
                    $response = Http::get('https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-093?Authorization=CWB-12E073F0-06A2-4F1E-BEB7-7FB421E605A2'.'&'.'locationId'.'='.$city->dataid.'&'.'locationName'.'='.$district->district_name);
                    $resdatas = $response->json();
                    if (count($resdatas) > 0) {
                        foreach($resdatas['records']['locations'] as $data) {
                            foreach($data['location'] as $location) {

                                $formData = [
                                    'id' => uniqid(),
                                    'district_id' => $district->id,
                                ];

                                foreach($location['weatherElement'] as $key => $weatherElement) {

                                    if (array_key_exists($weatherElement['elementName'], $weathersColumns)) {
                                        $column = $weathersColumns[$weatherElement['elementName']];
                                        foreach($weatherElement['time'] as $time) {
                                            if (isset($time['startTime']) && isset($time['endTime'])) {
                                                if ($time['startTime'] < date('Y-m-d H:i:s') && $time['endTime'] > date('Y-m-d H:i:s')) {
                                                    foreach($time['elementValue'] as $index => $elementValue) {
                                                        $formData['start_time'] = $time['startTime'];
                                                        $formData['end_time'] = $time['endTime'];
                                                        if ($elementValue['measures'] === '自定義 Wx 單位') {
                                                            $formData[$column] = intval($elementValue['value']);
                                                        }
                                                        if ($elementValue['measures'] === '百分比') {
                                                            $formData[$column] = intval($elementValue['value']);
                                                        }
                                                    }
                                                }
                                            } else if (isset($time['dataTime'])) {
                                                if ($time['dataTime'] < date('Y-m-d H:i:s')) {
                                                    $formData['dataTime'] = $time['dataTime'];
    
                                                    foreach($time['elementValue'] as $index => $elementValue) {
                                                        //TODO CI資料取得
                                                        $formData[$column] = intval($elementValue['value']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                       
                                }
                                $data = app(Weather::class)->create($formData); 
                            }
                        }
                    } else {
                        Log::info('無法取得資料');
                    }
    
                }
            }
            Log::info('天氣資料更新完成');
        } catch (Throwable $e) {
            Log::info($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
