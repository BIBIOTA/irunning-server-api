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
    public function test_example()
    {
        $filename = 'CityCountyData';

        $path = storage_path() . "/app/public" ."/${filename}.json";

        $json = json_decode(file_get_contents($path), true); 

        $distinct = ['釣魚臺', '南海島'];

        foreach($json as $county) {
            if (!in_array($county['CityName'], $distinct)) {
                $response = $this->call('GET', 'api/aqi', [
                    'County' => $county['CityName'],
                ]);
                $response->assertStatus(200);
                $response->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'SiteName',
                            'County',
                            'AQI',
                            'Pollutant',
                            'Status',
                            'SO2',
                            'CO',
                            'CO_8hr',
                            'PM10',
                            'PM2-5',
                            'NO2',
                            'NOx',
                            'NO',
                            'WindSpeed',
                            'WindDirec',
                            'PublishTime',
                            'PM2-5_AVG',
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
