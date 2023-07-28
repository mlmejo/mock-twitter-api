<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $tweet;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->hasTweets(10)
            ->create();
        $this->tweet = $this->user->tweets->first();
    }

    public function test_get_tweets(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get('/tweets');

        $response->assertOk();
    }

    public function test_create_tweet_without_attachment(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/tweets', [
                'message' => fake()->sentence(),
            ]);

        $response->assertNoContent();
    }


    public function test_show_tweet(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get('/tweets/' . $this->tweet->id);

        $response->assertOk();
    }

    public function test_delete_tweet(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->delete('/tweets/' . $this->tweet->id);

        $response->assertNoContent();
    }
}
