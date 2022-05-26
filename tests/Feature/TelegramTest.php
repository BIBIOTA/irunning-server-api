<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\TelegramFollowEvent;
use App\Services\TelegramUserService;
use App\Models\TelegramUser;
use App\Enum\SubscribeOptionEnum;
use Mockery\MockInterface;

class TelegramTest extends TestCase
{
    use RefreshDatabase;

    public function testFollow()
    {
        $input = [
            'userId' => 1,
            'eventId' => uniqid(),
        ];

        $response = $this->postJson('/api/telegram/follow', $input);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => null,
        ]);
    }

    public function testUnfollow()
    {
        $telegramUser = TelegramFollowEvent::factory()->create();

        $input = [
            'userId' => $telegramUser->telegram_id,
            'eventId' => $telegramUser->event_id,
        ];

        $response = $this->postJson('/api/telegram/unfollow', $input);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => null,
        ]);
    }

    public function testfollowingEvent()
    {
        $event = Event::factory()->create();

        $telegramUser = TelegramFollowEvent::factory()->create([
            'event_id' => $event->id,
        ]);

        $input = [
            'userId' => $telegramUser->telegram_id,
        ];

        $expectResult = $telegramUser->toArray();
        $expectResult['event'] = $event->toArray();

        $this->mock(TelegramUserService::class, function (MockInterface $mock) use ($expectResult) {
            $mock->shouldReceive('getFollowingEvent')->once()->andReturn($expectResult);
        });

        $response = $this->getJson('/api/telegram/followingEvent' . '?' . http_build_query($input));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => $expectResult,
        ]);
    }

    public function testSubscribeNewEvents()
    {
        $input = [
            'userId' => 1,
            'option' => SubscribeOptionEnum::NEWEVENTS,
        ];

        $response = $this->postJson('/api/telegram/subscribe', $input);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => null,
        ]);
    }

    public function testUnsubscribeNewEvents()
    {
        $TelegramUser = TelegramUser::factory()->create();

        $input = [
            'userId' => $TelegramUser->telegram_id,
            'option' => SubscribeOptionEnum::NEWEVENTS,
        ];

        $response = $this->postJson('/api/telegram/unsubscribe', $input);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => null,
        ]);
    }
}
