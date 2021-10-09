<?php

namespace App\Listeners;

use App\Contracts\PostContract;
use App\Repositories\PostRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class HandlePostCreation implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $event
     * @param PostContract $cache
     */
    public function handle($event)
    {
        $cache = new PostRepository();
        $cache->flush();
        $cache->regenerate();
    }
}
