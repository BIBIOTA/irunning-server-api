<?php

namespace Tests\Feature;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetNews()
    {
        Carbon::setTestNow();

        $news = News::factory()->create();

        $response = $this->get('/api/news');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => [
                $news->toArray(),
            ],
        ]);
    }

    public function testGetNewsNull()
    {
        $response = $this->get('/api/news');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'ok',
            'data' => [],
        ]);
    }
}
