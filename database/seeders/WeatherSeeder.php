<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\WeatherDocument;
use App\Models\WeatherData;
use App\Jobs\SendEmail;
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
        try {
            $datas = app(City::class)::select('id', 'dataid')->whereNotNull('dataid')->get();

            if ($datas->count() > 0) {
                foreach ($datas as $city) {
                    $districts = app(District::class)->where('city_id', $city->id)->get();

                    foreach ($districts as $district) {
                        $resdatas = $this->getHttpWeatherData($city->dataid, $district->district_name);

                        if (count($resdatas) > 0) {
                            foreach ($resdatas['records']['locations'] as $data) {
                                $district->dataid = $data['dataid'];
                                $district->updated_at = Carbon::now();
                                $district->save();

                                foreach ($data['location'] as $location) {
                                    foreach ($location['weatherElement'] as $key => $weatherElement) {
                                        if ($this->getColumnsKey($weatherElement['elementName'])) {
                                            $weather = app(WeatherDocument::class)
                                            ->where('name', $weatherElement['elementName'])
                                            ->first();

                                            if (!$weather) {
                                                $weatherformData = [
                                                    'id' => uniqid(),
                                                    'name' => $weatherElement['elementName'],
                                                    'description' => $weatherElement['description'],
                                                ];

                                                $weather = app(WeatherDocument::class)->create($weatherformData);
                                            }


                                            foreach ($weatherElement['time'] as $time) {
                                                if (isset($time['startTime']) && isset($time['endTime'])) {
                                                    $weatherData = app(WeatherData::class)
                                                        ->where('weather_document_id', $weather->id)
                                                        ->where('district_id', $district->id)
                                                        ->where('start_time', $time['startTime'])
                                                        ->where('end_time', $time['endTime'])
                                                        ->first();

                                                    if (!$weatherData) {
                                                        $data = [
                                                            'id' => uniqid(),
                                                            'weather_document_id' => $weather->id,
                                                            'district_id' => $district->id,
                                                            'start_time' => $time['startTime'],
                                                            'end_time' => $time['endTime'],
                                                            'value' => $this->getValue($weatherElement['elementName'], $time['elementValue'])
                                                        ];

                                                        $weatherData = app(WeatherData::class)->create($data);
                                                    }
                                                }
                                                if (isset($time['dataTime'])) {
                                                    $defaultEndTime = Carbon::parse($time['dataTime'])->addHours(3);

                                                    $weatherData = app(WeatherData::class)
                                                        ->where('weather_document_id', $weather->id)
                                                        ->where('district_id', $district->id)
                                                        ->where('start_time', $time['dataTime'])
                                                        ->where('end_time', $defaultEndTime)
                                                        ->first();

                                                    if (!$weatherData) {
                                                        $data = [
                                                            'id' => uniqid(),
                                                            'weather_document_id' => $weather->id,
                                                            'district_id' => $district->id,
                                                            'start_time' => $time['dataTime'],
                                                            'end_time' => $defaultEndTime,
                                                            'value' => $this->getValue($weatherElement['elementName'], $time['elementValue'])
                                                        ];

                                                        $weatherData = app(WeatherData::class)->create($data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            Log::stack(['weather', 'slack'])->error('??????????????????');
                            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'weather error log', 'main' => '??????????????????']);
                        }
                    }
                }
                Log::channel('weather')->info('????????????????????????');
            } else {
                Log::stack(['weather', 'slack'])->error('??????????????????:????????????????????????');
                SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'weather error log', 'main' => '??????????????????:????????????????????????']);
            }
        } catch (Throwable $e) {
            Log::stack(['weather', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'weather error log', 'main' => $e]);
        }
    }
}
