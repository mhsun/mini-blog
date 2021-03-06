@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-0 mt-2">
            <div class="col-md-12">
                <a href="{{ url('admin/posts/import') }}">
                    <button class="btn btn-primary float-right">Import Post</button>
                </a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 col-lg-offset-2 col-md-11 col-md-offset-1">
                @forelse($posts as $post)
                    <div class="post-preview">
                        <a href="{{ route('posts.show', $post->id) }}">
                            <h2 class="post-title">{{ $post->title }}</h2>
                            <h3 class="post-subtitle">
                                {{ showReadMore($post->description) }}
                            </h3>
                        </a>
                        <p class="post-meta">
                            Posted by <span>{{ $post->user->name }}</span>
                            on {{ \Illuminate\Support\Carbon::parse($post->publication_date)->toFormattedDateString() }}
                        </p>
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
