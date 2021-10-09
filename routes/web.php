<?php

use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserPostsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::redirect('/', '/posts');
Route::resource('/posts', PostsController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('posts', UserPostsController::class)->only(['index', 'create', 'store']);
    });

    Route::middleware('admin')->name('admin.')->prefix('admin')->group(function () {
        Route::get('posts', AdminPostsController::class);
    });
});
