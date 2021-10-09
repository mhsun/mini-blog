<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create();

        for ($i = 1; $i <= 100; $i++) {
            Post::factory()->create([
                'user_id'          => $user->id,
                'publication_date' => now()->addDays($i)
            ]);
        }
    }
}
