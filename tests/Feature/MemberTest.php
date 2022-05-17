<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Stat;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    public function testMemberRead()
    {
        $dataStructure = [
            'status',
            'message',
            'data' => [
                'county',
                'district',
                'email',
                'id',
                'joinRank',
                'nickname',
                'runnerType',
                'username',
            ],
        ];

        $member = Member::factory()->create();

        $token = Auth::guard()->fromUser($member);

        $response = $this->call('GET', 'api/member', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }

    public function testMemberUpdate()
    {
        $dataStructure = [
            'status',
            'message',
            'data',
        ];

        $member = Member::factory()->create();

        $token = Auth::guard()->fromUser($member);

        $formData = [
            'username' => '測試人',
            'nickname' => 'test@test.com',
            'email' => 'test@test.com',
            'county' => '屏東縣',
            'district' => '屏東市',
            'runnerType' => '1',
        ];

        $response = $this->call('PUT', 'api/member', $formData, [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }

    public function testGetIndexRunInfo()
    {
        $dataStructure = [
            'status',
            'message',
            'data' => [
                'monthDistance',
                'totalDistance',
                'weekDistance',
                'yearDistance',
            ],
        ];

        $member = Member::factory()->create();

        Stat::factory()->create([
            'member_id' => $member->id,
        ]);

        Activity::factory()->create([
            'member_id' => $member->id,
        ]);

        $token = Auth::guard()->fromUser($member);

        $response = $this->call('GET', 'api/member/getIndexRunInfo', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }
}
