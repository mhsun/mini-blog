<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserPostsController extends Controller
{
    public function index()
    {
        return view('pages.user.posts', [
            'posts' => Auth::user()->posts()->latest('publication_date')->simplePaginate()
        ]);
    }

    public function create()
    {
        return view('pages.user.form');
    }

    public function store(PostStoreRequest $request)
    {
        Auth::user()->posts()->create($request->validated());

        event(new PostCreated());

        return Redirect::route('user.posts.index')->with([
            'success' => 'Importing of posts has been started'
        ]);
    }
}
