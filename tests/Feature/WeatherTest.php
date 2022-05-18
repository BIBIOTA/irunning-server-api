<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\WxDocumentSeeder;
use App\Models\City;
use App\Models\District;
use App\Models\WeatherDocument;
use App\Models\WeatherData;
use App\Http\Controllers\Traits\WeatherTrait;
use Carbon\Carbon;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    use RefreshDatabase;
    use WeatherTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(WxDocumentSeeder::class);
    }

    public function testWeather()
    {
        $city = app(City::class)::select('id', 'dataid')->where('city_name', '臺北市')->first();

        $district = app(District::class)->where('district_name', '大安區')->first();

        $this->createSingleWeatherData($city, $district);

        $response = $this->call('GET', 'api/weather', [
            'district_id' => $district->id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'city',
                'district',
                'T',
                'AT',
                'PoP6h',
                'CI',
                'Wx',
                'WxValue',
                'updated_at',
            ]
        ]);
    }

    private function createSingleWeatherData(City $city, District $district)
    {
        $resdatas = $this->getHttpWeatherData($city->dataid, $district->district_name);

        if (count($resdatas) > 0) {
            foreach ($resdatas['records']['locations'] as $data) {
                $district->dataid = $data['dataid'];
                $district->updated_at = Carbon::now();
                $district->save();

                foreach ($data['location'] as $location) {
                    foreach ($location['weatherElement'] as $key => $weatherElement) {
                        if ($this->getColumnsKey($weatherElement['elementName'])) {
                            $weatherformData = [
                                'id' => uniqid(),
                                'name' => $weatherElement['elementName'],
                                'description' => $weatherElement['description'],
                            ];

                            $weather = WeatherDocument::factory()->create($weatherformData);


                            foreach ($weatherElement['time'] as $time) {
                                if (isset($time['startTime']) && isset($time['endTime'])) {
                                    $data = [
                                        'id' => uniqid(),
                                        'weather_document_id' => $weather->id,
                                        'district_id' => $district->id,
                                        'start_time' => $time['startTime'],
                                        'end_time' => $time['endTime'],
                                        'value' => $this->getValue(
                                            $weatherElement['elementName'],
                                            $time['elementValue']
                                        )
                                    ];

                                    WeatherData::factory()->create($data);
                                }
                                if (isset($time['dataTime'])) {
                                    $defaultEndTime = Carbon::parse($time['dataTime'])->addHours(3);

                                    $data = [
                                        'id' => uniqid(),
                                        'weather_document_id' => $weather->id,
                                        'district_id' => $district->id,
                                        'start_time' => $time['dataTime'],
                                        'end_time' => $defaultEndTime,
                                        'value' => $this->getValue(
                                            $weatherElement['elementName'],
                                            $time['elementValue'],
                                        )
                                    ];

                                    WeatherData::factory()->create($data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
