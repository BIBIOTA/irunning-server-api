<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberToken;
use App\Services\ActivityService;
use App\Jobs\GetActivitiesDataFromStrava;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery\MockInterface;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin()
    {
        Member::factory()->create();

        MemberToken::factory()->create();

        Bus::fake();

        $dataStructure = [
            'status',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ];

        $responseRefreashToken = $this->fakeRefrashStravaToken();

        $responseAthlete = $this->fakeResponseAthlete();

        $this->mock(ActivityService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getStats')->once()->andReturnNull();
        });

        $formData = array_merge($responseRefreashToken, ['athlete' => $responseAthlete]);

        $response = $this->call('POST', 'api/login', $formData);
        
        Bus::assertDispatched(GetActivitiesDataFromStrava::class);
        $response->assertStatus(200);
        $response->assertJsonStructure($dataStructure);
    }

    public function testLogout()
    {
        $member = Member::factory()->create();

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
