<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function fakeResponseAthlete()
    {
        return [
            'id' => 28179653,
            'username' => null,
            'resource_state' => 2,
            'firstname' => '裕揮',
            'lastname' => '太田',
            'bio' => '',
            'city' => '',
            'state' => '',
            'country' => null,
            'sex' => 'M',
            'premium' => false,
            'summit' => false,
            'created_at' => '2018-02-10T15:19:26Z',
            'updated_at' => '2019-10-14T00:54:04Z',
            'badge_type_id' => 0,
            'weight' => 56.4,
            'profile_medium' => 'https://graph.facebook.com/1512028328846999/picture?height=256&width=256',
            'profile' => 'https://graph.facebook.com/1512028328846999/picture?height=256&width=256',
            'friend' => null,
            'follower' => null,
        ];
    }

    public function fakeRefrashStravaToken()
    {
        return [
            'token_type' => 'Bearer',
            'access_token' => '6c0ab16df149b98685c098a850f921f760c0da65',
            'expires_at' => Carbon::now()->addHours(6)->timestamp,
            'expires_in' => 3600,
            'refresh_token' => '801228692fa6773627c1a5263838bdf1b4860e4e',
        ];
    }

    public function refreshStravaToken($data)
    {
        $responseRefreashToken = Http::post('https://www.strava.com/oauth/token', [
            'client_id' => '68055',
            'client_secret' => '4222100739f8aeecfe2bd2c2df077e5ec5a6b46c',
            'refresh_token' => $data->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        return $responseRefreashToken->json();
    }

    public function paginationTest($httpMethod, $url, $formData, $dataStructure, $server = [])
    {
        $pageCount = 1;
        for ($page = 1; $page <= $pageCount; $page++) {
            $formData = array_merge($formData, ['page' => $pageCount]);

            $response = $this->json(
                $httpMethod,
                $url,
                $formData,
                $server,
            );

            if ($response->getStatusCode() === 200) {
                $response->assertStatus(200);
                $response->assertJsonStructure($dataStructure);
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

    public function paginationStructure($data)
    {
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

    public function falseJsonStructure()
    {
        return [
            'message',
            'status',
            'data',
        ];
    }

    public function hasNextPage($response)
    {
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

    public function distinctCities()
    {
        return ['釣魚臺', '南海島'];
    }

    public function distinctDistricts()
    {
        return ['釣魚臺', '東沙群島','南沙群島'];
    }

    public function getDistrictsData()
    {
        $districts = app(District::class)->get();

        return $districts;
    }

    public function getCityCountyData()
    {
        $cities = app(City::class)->get();

        return $cities;
    }
}
