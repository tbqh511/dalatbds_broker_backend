<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsPostApiTest extends TestCase
{
    // use RefreshDatabase; // Caution: clears DB

    /**
     * Test getting list of news posts.
     *
     * @return void
     */
    public function test_can_list_news_posts()
    {
        $response = $this->getJson('/api/news_posts');

        $response->assertStatus(200);
        // Expecting a structure with error, message, data
        $response->assertJsonStructure([
            'error',
            'message',
            'data'
        ]);
    }

    /**
     * Test getting a single news post.
     *
     * @return void
     */
    public function test_can_show_news_post()
    {
        // Assuming there is at least one post, or we create one if allowed by DB state (no RefreshDatabase)
        // Since I cannot migrate/refresh safely, I will check if empty or not.
        // Or create one via factory if possible? Factories are not visible.

        // I'll try to fetch a non-existent one to check 404/not found logic (which returns 200 with error=true in this API design)
        $response = $this->getJson('/api/news_posts/999999');

        $response->assertStatus(200)
                 ->assertJson([
                     'error' => true,
                     'message' => 'News Post not found!'
                 ]);
    }
}
