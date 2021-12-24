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
        $filename = 'CityCountyData';

        $path = storage_path() . "/app/public" ."/${filename}.json";

        $json = json_decode(file_get_contents($path), true); 

        $distinct = ['釣魚臺', '南海島'];

        foreach($json as $county) {
            if (!in_array($county['CityName'], $distinct)) {
                $response = $this->call('GET', 'api/districts', [
                    'CityName' => $county['CityName'],
                ]);
                $response->assertStatus(200);
                $response->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'CityName',
                            'AreaName',
                        ],
                    ]
                ]);   
            }
        }
    }
}
