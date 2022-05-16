<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Banner;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetBanners()
    {
        $banner = Banner::factory()->create();

        $response = $this->json('GET', 'api/banner');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => [
                $banner->toArray(),
            ]
        ]);
    }

    public function testGetBannersNull()
    {
        $response = $this->json('GET', 'api/banner');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => []
        ]);
    }
}
