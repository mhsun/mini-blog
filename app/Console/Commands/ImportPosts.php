<?php

namespace App\Console\Commands;

use App\Contracts\PostContract;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:posts
                            {url? : URL from the data will fetched}
                            {user? : User id against whom the data will be saved}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import post from another site';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PostContract $postRepo)
    {
        if ($posts = $this->getDataFromUrl()) {
            try {
                DB::transaction(function () use ($posts) {
                    $this->getUser()->posts()->createMany($posts);
                }, 3);

                $this->info("Total " . count($posts) . " row(s) imported.");

                $this->regenerateCache($postRepo);
            } catch (\Exception $exception) {
                $this->warn("Failed to import data. Reverting to original state.");
            }
        }
        return 0;
    }

    private function getDataFromUrl()
    {
        $url = $this->argument('url') ?? config('admin.import_url');

        $this->line("Reading data from url..");

        try {
            $response = Http::retry(3, 100)->get($url);

            if ($response->successful()) {
                $this->line("Data received. Processing to import...");
                return $response->json()['data'];
            }
        } catch (\Exception $exception) {
            $this->error('Failed to read data from url');
        }

        return [];
    }

    private function getUser()
    {
        if ($userId = $this->argument('user')) {
            return User::find($userId);
        }
        return User::whereEmail(config('admin.email'))->first();
    }

    private function regenerateCache($postRepo)
    {
        $postRepo->flush();
        $postRepo->regenerate();
    }
}
