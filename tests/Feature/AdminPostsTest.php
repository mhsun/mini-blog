<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Notifications\SendImportStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminPostsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin, $randomUser;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->admin, $this->randomUser] = User::factory()->count(2)->create();

        $this->admin->is_admin = true;
        $this->admin->save();
    }

    /** @test */
    public function only_user_with_role_admin_can_visit_admin_panel()
    {
        $this->actingAs($this->randomUser, 'web');

        $this->get('/admin/posts')
            ->assertStatus(403);

        $this->actingAs($this->admin, 'web');

        $this->get('/admin/posts')
            ->assertOk();
    }

    /** @test */
    public function admin_can_see_all_published_post()
    {
        $this->actingAs($this->admin, 'web');

        $posts = Post::factory()->count(5)->create();

        $this->get('/admin/posts')
            ->assertViewIs('pages.admin.posts')
            ->assertSee($posts->random()->title);
    }

    /** @test */
    public function admin_can_import_posts_from_other_site()
    {
        $this->actingAs($this->admin, 'web');

        $this->assertCount(0, DB::table('posts')->get());

        Notification::fake();

        Http::fake([
            config('admin.import_url') => Http::response([
                'data' => [
                    [
                        'title'            => $this->faker->sentence,
                        'description'      => $this->faker->paragraph,
                        'publication_date' => now()->addDay()->toDateTimeString(),
                    ]
                ]
            ]),
        ]);

        Notification::assertNothingSent();

        $this->get('/admin/posts/import')
            ->assertStatus(302);

        Notification::assertSentTo($this->admin, SendImportStatusNotification::class);

        $this->assertCount(1, DB::table('posts')->get());
    }
}
