<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistrictsTest extends TestCase
{
    use RefreshDatabase;

    public function testDistricts()
    {
        $cities = $this->getCityCountyData();

        $distinct = $this->distinctCities();

        foreach ($cities as $city) {
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
