<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportPostsCommandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

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
    }

    /** @test */
    public function data_can_be_imported_via_artisan_command()
    {
        $this->assertCount(0, DB::table('posts')->get());

        $user = User::factory()->create();

        Artisan::call("import:posts", [
            'user' => $user->id
        ]);

        $this->assertCount(1, DB::table('posts')->get());
    }

    /** @test */
    public function posts_will_be_imported_against_default_account_if_no_user_passed()
    {
        $user = User::factory()->create([
            'email' => config('admin.email')
        ]);

        $posts = DB::table('posts')->where('user_id', $user->id)->get();

        $this->assertCount(0, $posts);

        Artisan::call("import:posts", [
            'user' => $user->id
        ]);

        $posts = DB::table('posts')->where('user_id', $user->id)->get();

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function no_data_will_be_imported_if_url_is_invalid_or_any_errors_occurs()
    {
        $this->assertCount(0, DB::table('posts')->get());

        $user = User::factory()->create();

        Artisan::call("import:posts", [
            'user' => $user->id,
            'url'  => 'http://somefakeurl.example'
        ]);

        $this->assertCount(0, DB::table('posts')->get());
    }
}
