<?php

namespace App\Http\Controllers;

use App\Jobs\ImportPosts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PostsImportController extends Controller
{
    public function __invoke()
    {
        ImportPosts::dispatch(Auth::user());

        return Redirect::back()->with([
            'success' => 'Importing of posts has been started. Try refreshing the page after some time.'
        ]);
    }
}
