<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeatherTest extends TestCase
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

        $distinct = ['釣魚臺', '東沙群島','南沙群島'];

        foreach($json as $county) {
            foreach($county['AreaList'] as $district) {
                if (!in_array($district['AreaName'], $distinct))
                $response = $this->call('GET', 'api/weather', [
                    'CityName' => $county['CityName'],
                    'AreaName' => $district['AreaName'],
                ]);
                $response->assertStatus(200);
                $response->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'CityName',
                        'AreaName',
                        'temperature',
                        'AT',
                        'PoP6h',
                        'CI',
                        'Wx',
                        'WxValue',
                        'updated_at',
                    ]
                ]);
            }
        }

        

    }
}
