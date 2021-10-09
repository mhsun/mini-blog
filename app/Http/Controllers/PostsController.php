<?php

namespace App\Http\Controllers;

use App\Contracts\PostContract;
use App\Filters\PostFilter;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public $postRepo;

    public function __construct(PostContract $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    public function index(Request $request, PostFilter $filter)
    {
        return view('pages.posts.all', [
            'posts' => $this->postRepo->getAll(
                $filter->getPageNumber($request), $filter->getSortByKey($request)
            )
        ]);
    }

    public function show(int $post)
    {
        return view('pages.posts.single', [
            'post' => $this->postRepo->getById($post)
        ]);
    }
}
