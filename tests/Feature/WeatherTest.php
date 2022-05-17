<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\WeatherSeeder;
use Database\Seeders\WxDocumentSeeder;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(WxDocumentSeeder::class);
        $this->seed(WeatherSeeder::class);
    }

    public function testWeather()
    {
        $cities = $this->getCityCountyData();

        
        $distinct = $this->distinctDistricts();

        foreach ($cities as $city) {
            $districts = $this->getDistrictsData($city->id);
            foreach ($districts as $district) {
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
                        ]
                    ]);
                    break;
                }
            }
        }
    }
}
