<?php

namespace App\Console\Commands;

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

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新天氣資料';

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
                            foreach($resdatas['records']['locations'] as $data) {
                                foreach($data['location'] as $location) {
                                    $data = app(Weather::class)
                                    ->where('locationsName', $city->CityName)
                                    ->where('locationName', $district->AreaName)
                                    ->first();
                                    $id = uniqid();
                                    $formData = [
                                        'id' => $id,
                                        'locationsName' => $city->CityName,
                                        'dataid' => $data['dataid'],
                                        'locationName' => $city->dataid,
                                    ];
                                    if ($data) {
                                        $id = $data->id;
                                        $data->updated_at = Carbon::now();
                                        $data->save();
                                    } else {
                                        $formData['$id'] = uniqid();
                                        $data = app(Weather::class)->create($formData);
                                    }
                                    foreach($location['weatherElement'] as $key => $weatherElement) {
                                        $elementData = [];
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
                                                    $elementData['value'] = intval($elementValue['value']);
                                                    $elementData['measures'] = $elementValue['measures'];
                                                } else if ($key !== 'Wx') {
                                                    foreach($elementValue as $obj => $value) {
                                                        $elementData[$obj] = $value;
                                                    }
                                                }
                                            }
                                        }
                                        $elementData['weather_id'] = $id;
                                        $elementData['description'] = $weatherElement['description'];
                                        $elementData['created_at'] = Carbon::now();
                                        $elementData['updated_at'] = Carbon::now();
                                        $key = $weatherElement['elementName'];
                                        $oldData = app('App\Models\\'.$key)->where('weather_id', $id)->update($elementData);
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


        return 0;
    }
}
