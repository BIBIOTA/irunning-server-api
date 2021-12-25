<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function refreshStravaToken($data) {
        $responseRefreashToken = Http::post('https://www.strava.com/oauth/token', [
            'client_id' => '68055',
            'client_secret' => '4222100739f8aeecfe2bd2c2df077e5ec5a6b46c',
            'refresh_token' => $data->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        return $responseRefreashToken->json();
    }

    public function paginationTest($httpMethod, $url, $formData, $dataStructure) {
        $pageCount = 1;
        for ($page = 1; $page <= $pageCount; $page++) {
            
            $formData = array_merge($formData, ['page' => $pageCount]);

            $response = $this->call($httpMethod, $url, $formData);
            if ($response->getStatusCode() === 200) {
                $response->assertStatus(200);
                $response->assertJsonStructure($dataStructure);
                logger($this->hasNextPage($response));
                if ($this->hasNextPage($response)) {
                    $pageCount++;
                }
            } else {
                $response->assertStatus(404);
                $response->assertJsonStructure($this->falseJsonStructure());
                break;
            }
        }
    }

    public function paginationStructure($data) {
        $structure = [
            'status',
            'message',
            'data' => [
                'current_page',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
                'data' => $data,
            ]
        ];
        return $structure;    
    }

    public function falseJsonStructure() {
        return [
            'message',
            'status',
            'data',
        ];
    }

    public function hasNextPage($response) {
        $datas = $response->json();
        if ($datas) {
            if ($datas['data']) {
                if ($datas['data']['current_page'] < $datas['data']['last_page']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function distinctCities () {
        return ['釣魚臺', '南海島'];
    }

    public function distinctDistricts () {
        return ['釣魚臺', '東沙群島','南沙群島'];
    }

    public function getCityCountyData() {
        $filename = 'CityCountyData';

        $path = storage_path() . "/app/public" ."/${filename}.json";

        $json = json_decode(file_get_contents($path), true);

        return $json;
    }
}
