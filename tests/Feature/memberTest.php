<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Artisan;

class MemberTest extends TestCase
{
    use DatabaseTransactions;

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

        $member = app(Member::class)->where('id', env('API_MEMBER_ID'))->first();

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

        $member = app(Member::class)->where('id', env('API_MEMBER_ID'))->first();

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

        $member = app(Member::class)->where('id', env('API_MEMBER_ID'))->first();

        $token = Auth::guard()->fromUser($member);

        $response = $this->call('GET', 'api/member/getIndexRunInfo', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []);

        $response
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }
}
