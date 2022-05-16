<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TelegramUser;
use App\Enum\SubscribeOptionEnum;

class TelegramTest extends TestCase
{
    use RefreshDatabase;

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
