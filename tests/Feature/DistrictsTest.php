<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class DistrictsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $cities = $this->getCityCountyData();

        $distinct = $this->distinctCities();

        foreach($cities as $city) {
            if (!in_array($city->city_name, $distinct)) {
                $response = $this->call('GET', 'api/districts', [
                    'city_id' => $city->id,
                ]);
                $response->assertStatus(200);
                $response->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'city_id',
                            'district_name',
                        ],
                    ]
                ]);   
            }
        }
    }
}
