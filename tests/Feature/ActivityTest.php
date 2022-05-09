<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Artisan;

class ActivityTest extends TestCase
{
    public function testActivities()
    {
        $dataStructure = $this->paginationStructure([
            '*' => [
                'id',
                'name',
                'pace',
                'distance',
                'moving_time',
                'start_date_local',
                'summary_polyline',
            ],
        ]);


        $member = app(Member::class)->where('id', env('API_MEMBER_ID'))->first();
        $token = Auth::guard()->fromUser($member);

        $this->paginationTest('GET', 'api/activities', [], $dataStructure, ['HTTP_Authorization' => 'Bearer ' . $token]);
    }

    public function testActivity()
    {
        $dataStructure = [
            'status',
            'message',
            'data',
        ];

        $member = app(Member::class)->where('id', env('API_MEMBER_ID'))->first();
        $token = Auth::guard()->fromUser($member);

        $activity = app(Activity::class)->where('member_id', env('API_MEMBER_ID'))->inRandomOrder()->first();

        $response = $this->call('GET', 'api/activities/' . $activity->id, [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }
}
