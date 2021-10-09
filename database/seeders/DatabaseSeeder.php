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
        \App\Models\User::factory()->create([
            'name'     => config('admin.name'),
            'email'    => config('admin.email'),
            'is_admin' => true
        ]);

        Post::factory()->count(1000)->create([
            'publication_date' => now()
        ]);
    }
}
