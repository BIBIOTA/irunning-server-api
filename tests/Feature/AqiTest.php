<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AqiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAqi()
    {
        $cities = $this->getCityCountyData();

        $distinct = $this->distinctCities();

        foreach ($cities as $city) {
            if (!in_array($city->city_name, $distinct)) {
                $response = $this->call('GET', 'api/aqi', [
                    'city_id' => $city->id,
                ]);
                $response->assertStatus(200);
                $response->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'city_id',
                            'SiteName',
                            'AQI',
                            'Pollutant',
                            'Status',
                            'SO2',
                            'CO',
                            'CO_8hr',
                            'PM10',
                            'PM2_5',
                            'NO2',
                            'NOx',
                            'NO',
                            'WIND_SPEED',
                            'WIND_DIREC',
                            'PublishTime',
                            'PM2_5_AVG',
                            'PM10_AVG',
                            'SO2_AVG',
                            'Longitude',
                            'Latitude',
                            'SiteId',
                            'ImportDate',
                        ],
                    ]
                ]);
            }
        }
    }
}
