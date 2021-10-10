<?php

namespace App\Jobs;

use App\Contracts\PostContract;
use App\Models\User;
use App\Notifications\SendImportStatusNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PostContract $postRepo)
    {
        if ($posts = $this->getDataFromUrl()) {
            try {
                $success = DB::transaction(function () use ($posts) {
                    $this->user->posts()->createMany($posts);
                    return true;
                }, $this->tries);

                if ($success) {
                    $this->cacheAndNotify($postRepo);
                }
            } catch (\Exception $exception) {
                Log::error('job-error', [$exception->getMessage()]);
                $this->user->notify(new SendImportStatusNotification(false));
            }
        } else {
            $this->user->notify(new SendImportStatusNotification(false));
        }
    }

    private function getDataFromUrl()
    {
        try {
            $response = Http::retry($this->tries, 100)->get(config('admin.import_url'));

            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $exception) {
            Log::error('log.error', [$exception->getMessage()]);
        }

        return [];
    }

    private function cacheAndNotify($postRepo)
    {
        $postRepo->flush();
        $postRepo->regenerate();
        $this->user->notify(new SendImportStatusNotification(true));
    }
}
