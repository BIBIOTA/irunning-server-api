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

        $json = $this->getCityCountyData();

        $distinct = $this->distinctDistricts();

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
