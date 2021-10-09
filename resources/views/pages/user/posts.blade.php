@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-0 mt-2">
            <div class="col-md-12">
                <a href="{{ route('user.posts.create') }}"><button class="btn btn-primary float-right">+ Add New Post</button></a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-2 col-md-11 col-md-offset-1">
                @forelse($posts as $post)
                    <div class="post-preview">
                        <a href="{{ route('posts.show', $post->id) }}">
                            <h2 class="post-title">{{ $post->title }}</h2>
                        </a>
                        <p class="post-meta">
                            Posted
                            on {{ \Illuminate\Support\Carbon::parse($post->publication_date)->toFormattedDateString() }}
                        </p>
                    </div>
                    <div class="text-justify">
                        {{ $post->description }}
                    </div>
                    <hr>
                @empty
                    No post yet.
            @endforelse

            <!-- Pager -->
                <ul class="pager float-right mb-5">
                    {{ $posts->links() }}
                </ul>
            </div>
        </div>
    </div>
@endsection
