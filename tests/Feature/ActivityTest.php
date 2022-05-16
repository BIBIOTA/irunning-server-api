<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Activity;
use App\Services\ActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

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

        $member = Member::factory()->create();

        $token = Auth::guard()->fromUser($member);

        Activity::factory()->create([
            'member_id' => $member->id,
        ]);

        $this->paginationTest('GET', 'api/activities', [], $dataStructure, ['HTTP_Authorization' => 'Bearer ' . $token]);
    }

    public function testActivity()
    {
        $dataStructure = [
            'status',
            'message',
            'data',
        ];

        $member = Member::factory()->create();

        $token = Auth::guard()->fromUser($member);

        $activity = Activity::factory()->create([
            'member_id' => $member->id,
        ]);

        $this->mock(ActivityService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getActivityFromStrava')->once()->andReturn([]);
        });

        $response = $this->call('GET', 'api/activities/' . $activity->id, [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }
}
