<?php

namespace App\Http\Controllers;

use App\Contracts\PostContract;
use App\Filters\PostFilter;
use Illuminate\Http\Request;

class AdminPostsController extends Controller
{
    public $postRepo;

    public function __construct(PostContract $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    public function __invoke(Request $request, PostFilter $filter)
    {
        return view('pages.admin.posts', [
            'posts' => $this->postRepo->getAll(
                $filter->getPageNumber($request), $filter->getSortByKey($request)
            )
        ]);
    }
}
