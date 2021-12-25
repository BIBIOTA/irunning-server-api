<?php

namespace Tests\Feature;

use App\Models\MemberToken;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

use Carbon\Carbon;
use Artisan;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        
        $stravaAthleteUrl = 'https://www.strava.com/api/v3/athlete';

        $datas = app(MemberToken::class)->get();

        if ($datas->count() > 0) {
            foreach($datas as $data) {   

                $responseRefreashToken = $this->refreshStravaToken($data);
                
                $responseAthlete = Http::withToken($data->access_token)->get($stravaAthleteUrl);

                $responseAthlete = $responseAthlete->json();

                $formData = array_merge($responseRefreashToken, ['athlete' => $responseAthlete]);

                $response = $this->call('POST', 'api/login/login', $formData);
                $response->assertStatus(200);
                $response->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'badge_type_id',
                        'city',
                        'country',
                        'county',
                        'created_at',
                        'district',
                        'email',
                        'expires_at',
                        'firstname',
                        'id',
                        'is_register',
                        'join_rank',
                        'lastname',
                        'login_from',
                        'nickname',
                        'resource_state',
                        'runner_type',
                        'sex',
                        'siteName',
                        'state',
                        'strava_id',
                        'updated_at',
                        'username',
                        'weight',
                    ],
                ]);
            }
        }

    }
}
