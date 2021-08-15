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
        
    
            $datas = app(City::class)::select('dataid', 'CityName')->whereNotNull('dataid')->get();
            foreach($datas as $city) {
                $districts = app(District::class)->where('CityName', $city->CityName)->get();
                foreach($districts as $district) {
                    $response = Http::get('https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-093?Authorization=CWB-12E073F0-06A2-4F1E-BEB7-7FB421E605A2'.'&'.'locationId'.'='.$city->dataid.'&'.'locationName'.'='.$district->AreaName);
                    $resdatas = $response->json();
                    if (count($resdatas) > 0) {
                        app(Weather::class)->truncate();
                        app(AT::class)->truncate();
                        app(CI::class)->truncate();
                        app(PoP6h::class)->truncate();
                        app(PoP12h::class)->truncate();
                        app(RH::class)->truncate();
                        app(T::class)->truncate();
                        app(Td::class)->truncate();
                        app(WD::class)->truncate();
                        app(WeatherDescription::class)->truncate();
                        app(WS::class)->truncate();
                        app(Wx::class)->truncate();            
                        foreach($resdatas['records']['locations'] as $data) {
                            foreach($data['location'] as $location) {
                                $id = uniqid();
                                $formData = [
                                    'id' => $id,
                                    'locationsName' => $data['locationsName'],
                                    'dataid' => $data['dataid'],
                                    'locationName' => $location['locationName'],
                                ];
                                $data = app(Weather::class)->create($formData);
                                foreach($location['weatherElement'] as $key => $weatherElement) {
                                    $elementData = [
                                        'weather_id' => $id,
                                        'description' => $weatherElement['description'],
                                    ];
                                    $key = $weatherElement['elementName'];
                                    foreach($weatherElement['time'] as $time) {
                                        if (isset($time['startTime']) && isset($time['endTime'])) {
                                            if ($time['startTime'] < date('Y-m-d H:i:s') && $time['endTime'] > date('Y-m-d H:i:s')) {
                                                $elementData['startTime'] = $time['startTime'];
                                                $elementData['endTime'] = $time['endTime'];
                                            }
                                        } else if (isset($time['dataTime'])) {
                                            if ($time['dataTime'] < date('Y-m-d H:i:s')) {
                                                $elementData['dataTime'] = $time['dataTime'];
                                            }
                                        }
                                        foreach($time['elementValue'] as $index => $elementValue) {
                                            if ($key === 'Wx' && $index === 0) {
                                                $elementData['value'] = $elementValue['value'];
                                                $elementData['measures'] = $elementValue['measures'];
                                            } else if ($key !== 'Wx') {
                                                foreach($elementValue as $obj => $value) {
                                                    $elementData[$obj] = $value;
                                                }
                                            }
                                        }
                                    }
                                    $elementData['created_at'] = Carbon::now();
                                    $elementData['updated_at'] = Carbon::now();
                                    app('App\Models\\'.$key)->create($elementData);
                                }
                            }
                        }
                    } else {
                        Log::info('無法取得資料');
                    }
    
                }
            }
    
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::info('天氣資料更新完成');
        } catch (Throwable $e) {
            Log::info($e);
        }
    }
}
