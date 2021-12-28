<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Weather;
use App\Models\WeatherDetail;
use App\Models\WeatherData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Http\Controllers\Traits\WeatherTrait;

class WeatherSeeder extends Seeder
{
    use WeatherTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            
            $datas = app(City::class)::select('id', 'dataid')->whereNotNull('dataid')->get();

            foreach($datas as $city) {

                $districts = app(District::class)->where('city_id', $city->id)->get();

                foreach($districts as $district) {

                    $resdatas = $this->getHttpWeatherData($city->dataid, $district->district_name);

                    if (count($resdatas) > 0) {
                        foreach($resdatas['records']['locations'] as $data) {
                            foreach($data['location'] as $location) {

                                foreach($location['weatherElement'] as $key => $weatherElement) {

                                    $weather = app(Weather::class)
                                        ->where('district_id', $district->id)
                                        ->where('name', $weatherElement['elementName'])
                                        ->where('description', $weatherElement['description'])
                                        ->first();
                                    
                                    if (!$weather) {
                                        $weatherformData = [
                                            'id' => uniqid(),
                                            'district_id' => $district->id,
                                            'name' => $weatherElement['elementName'],
                                            'description' => $weatherElement['description'],
                                        ];
    
                                        $weather = app(Weather::class)->create($weatherformData);
                                    }


                                    foreach($weatherElement['time'] as $time) {
                                        if (isset($time['startTime']) && isset($time['endTime'])) {

                                            $weatherDetail = app(WeatherDetail::class)
                                                            ->where('weather_id', $weather->id)
                                                            ->where('start_time', $time['startTime'])
                                                            ->where('end_time', $time['endTime'])
                                                            ->first();

                                            if (!$weatherDetail) {
                                                $detailFormData = [
                                                    'id' => uniqid(),
                                                    'weather_id' => $weather->id,
                                                    'start_time' => $time['startTime'],
                                                    'end_time' => $time['endTime'],
                                                ];
                                                $weatherDetail = app(WeatherDetail::class)->create($detailFormData);
    
                                                foreach($time['elementValue'] as $index => $elementValue) {
                                                    
                                                    $data = [
                                                        'id' => uniqid(),
                                                        'weather_detail_id' => $weatherDetail->id,
                                                        'measures' => $elementValue['measures'],
                                                        'value' => $elementValue['value']
                                                    ];
    
                                                    app(WeatherData::class)->create($data);
                                                }
                                            }

                                        }
                                        if (isset($time['dataTime'])) {

                                            $weatherDetail = app(WeatherDetail::class)
                                                            ->where('weather_id', $weather->id)
                                                            ->where('data_time', $time['dataTime'])
                                                            ->first();

                                            if (!$weatherDetail) {
                                                $detailFormData = [
                                                    'id' => uniqid(),
                                                    'weather_id' => $weather->id,
                                                    'data_time' => $time['dataTime'],
                                                ];
    
                                                $weatherDetail = app(WeatherDetail::class)->create($detailFormData);
                                                
                                                foreach($time['elementValue'] as $index => $elementValue) {
                                                    $data = [
                                                        'id' => uniqid(),
                                                        'weather_detail_id' => $weatherDetail->id,
                                                        'measures' => $elementValue['measures'],
                                                        'value' => $elementValue['value']
                                                    ];
    
                                                    app(WeatherData::class)->create($data);
                                                }
                                            }

                                        }
                                    }
                                        
                                }
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
