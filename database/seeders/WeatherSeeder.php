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

class WeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

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


        $datas = app(City::class)::select('dataid', 'CityName')->whereNotNull('dataid')->get();
        foreach($datas as $city) {
            $districts = app(District::class)->where('CityName', $city->CityName)->get();
            foreach($districts as $district) {
                $response = Http::get('http://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-093?Authorization=CWB-64ECC965-374F-40DB-90C3-9A2E793CE3DE'.'&'.'locationId'.'='.$city->dataid.'&'.'locationName'.'='.$district->AreaName);
                $resdatas = $response->json();
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
                                    $elementData['startTime'] = $time['startTime'];
                                    $elementData['endTime'] = $time['endTime'];
                                } else if (isset($time['dataTime'])) {
                                    $elementData['dataTime'] = $time['dataTime'];
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

            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
