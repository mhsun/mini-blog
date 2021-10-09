<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $posts;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->posts = Post::factory()->count(10)->create();
    }

    /** @test */
    public function user_will_be_redirected_to_posts_route_on_initial_visit()
    {
        $this->get('/')
            ->assertRedirect('/posts');
    }

    /** @test */
    public function anyone_can_see_posts_without_logging_into_the_system()
    {
        $randomPost = $this->posts->random();

        $this->get('/posts')
            ->assertOk()
            ->assertSee($randomPost->title);
    }

    /** @test */
    public function publisher_name_and_date_will_be_visible_with_every_post()
    {
        $randomPost = $this->posts->random();

        $this->get('/posts')
            ->assertOk()
            ->assertSee($randomPost->user->name)
            ->assertSee($randomPost->user->publication_date);
    }

    /** @test */
    public function user_can_see_single_view_of_a_post()
    {
        $randomPost = $this->posts->random();

        $this->get("/posts/{$randomPost->id}")
            ->assertOk()
            ->assertSee($randomPost->user->name)
            ->assertSee($randomPost->user->publication_date);
    }
}
