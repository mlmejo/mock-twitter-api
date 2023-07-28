<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowController extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $toFollow;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();;
        $this->toFollow = User::factory()->create();
    }

    public function test_follow_user(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/users/' . $this->user->id . '/followers');

        $response->assertNoContent();
    }

    public function test_get_user_followers(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get('/users/' . $this->user->id . '/followers');

        $response->assertNoContent();
    }

    public function test_unfollow_user(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->get('/users/' . $this->user->id . '/followers');

        $response->assertNoContent();
    }
}
