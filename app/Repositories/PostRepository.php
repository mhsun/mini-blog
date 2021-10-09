<?php

namespace App\Repositories;

use App\Contracts\PostContract;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostRepository implements PostContract
{
    /**
     * key name to store paginated pages
     *
     * @var string
     */
    public $cacheKey = "posts.pages.";

    /**
     * Key name to store tagged based posts
     *
     * @var string
     */
    public $cacheTag = "posts.sort.";

    /**
     * Key name to store a single post
     *
     * @var string
     */
    public $postKey = "posts.";

    /**
     * Duration in seconds that the key(s) will store in the memory
     *
     * @var int
     */
    public $ttl = 600;

    /**
     * Number of row to be picked
     *
     * @var int
     */
    public $limit = 20;

    /**
     * Generate key name for caching
     *
     * @param $key
     *
     * @return string
     */
    protected function getKeyName($key): string
    {
        return $this->cacheKey . $key;
    }

    /**
     * Generate key name for caching
     *
     * @param $sortBy
     *
     * @return string
     */
    public function getTagKeyName($sortBy): string
    {
        return $this->cacheTag . $sortBy;
    }

    /**
     * Generate key name for caching
     *
     * @param $id
     *
     * @return string
     */
    protected function getPostKeyName($id): string
    {
        return $this->postKey . $id;
    }

    public function getAll(int $page, $sort = 'desc')
    {
        return Cache::tags($this->getTagKeyName($sort))->remember(
            $this->getKeyName($page),
            $this->ttl,
            function () use ($page, $sort) {
                return Post::with('user:id,name')
                    ->orderBy('publication_date', $sort)
                    ->simplePaginate(20, ['*'], 'page', $page);
            });
    }

    public function getById(int $id)
    {
        return Cache::remember($this->getPostKeyName($id), $this->ttl, function () use ($id) {
            return Post::with('user:id,name')->find($id);
        });
    }

    public function flush(string $key = null): void
    {
        if ($key) {
            Cache::tags($this->getTagKeyName($key))->flush();
        } else {
            Cache::tags($this->getTagKeyName('desc'))->flush();
        }
    }

    public function regenerate(): void
    {
        $page = 1;
        $sort = 'desc';

        Cache::tags($this->getTagKeyName($sort))->remember(
            $this->getKeyName($page),
            $this->ttl,
            function () use ($page, $sort) {
                return Post::with('user:id,name')
                    ->orderBy('publication_date', $sort)
                    ->simplePaginate(20, ['*'], 'page', $page);
            });
    }
}
