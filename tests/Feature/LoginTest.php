<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Artisan;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $stravaAthleteUrl = 'https://www.strava.com/api/v3/athlete';

        $dataStructure = [
            'status',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ];

        $member = app(MemberToken::class)->where('member_id', env('API_MEMBER_ID'))->first();

        $responseRefreashToken = $this->refreshStravaToken($member);


        $responseAthlete = Http::withToken($member->access_token)->get($stravaAthleteUrl);

        $responseAthlete = $responseAthlete->json();

        $formData = array_merge($responseRefreashToken, ['athlete' => $responseAthlete]);

        $response = $this->call('POST', 'api/login', $formData);

        $response->assertStatus(200);
        $response->assertJsonStructure($dataStructure);
    }

    public function testLogout()
    {
        $memberId = env('API_MEMBER_ID');

        $member = app(Member::class)->where('id', env('API_MEMBER_ID'))->first();

        $token = Auth::guard()->fromUser($member);

        $response = $this->call('POST', 'api/logout', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
        ]);
    }
}
