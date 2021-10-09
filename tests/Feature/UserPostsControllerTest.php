<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPostsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function only_authenticated_user_can_publish_post()
    {
        $this->get('/user/posts/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_publish_post()
    {
        $this->actingAs($this->user, 'web');

        $payload = [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->get('/user/posts')
            ->assertDontSee($payload['title']);

        $this->post('/user/posts', $payload)
            ->assertRedirect('/user/posts');

        $this->get('/user/posts')
            ->assertSee($payload['title']);
    }

    /** @test */
    public function user_can_see_his_all_published_post()
    {
        $this->actingAs($this->user, 'web');

        $posts = Post::factory()->count(5)->create([
            'user_id' => $this->user->id
        ]);

        $this->get('/user/posts')
            ->assertSee($posts->random()->title);
    }
}
