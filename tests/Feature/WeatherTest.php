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

        $cities = $this->getCityCountyData();

        $distinct = $this->distinctDistricts();

        foreach($cities as $city) {
            $districts = $this->getDistrictsData($city->id);
            foreach($districts as $district) {
                if (!in_array($district->district_name, $distinct)) {
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
                            'start_time',
                            'end_time',
                        ]
                    ]);
                }
            }
        }

        

    }
}
