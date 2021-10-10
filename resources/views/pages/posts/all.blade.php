@extends('layouts.app')

@section('content')
    <div class="container">
        @if (count($posts) > 0)
            <div class="row">
                <div class="col-12 p-3">
                    <form action="{{ getSortByRoute() }}" method="get">
                        <div class="form-group row">
                            <div class="col-8">
                                <label for="sort">Sort By Published Date</label>
                                <select name="sort" class="form-control">
                                    <option value="desc" {{ request()->query('sort') != 'asc' ? 'selected' : '' }}>
                                        Latest
                                    </option>
                                    <option value="asc" {{ request()->query('sort') == 'asc' ? 'selected' : '' }}>Oldest
                                    </option>
                                </select>
                            </div>
                            <div class="col-4">
                                <p></p>
                                <button type="submit" class="btn btn-xs btn-primary">Show Result</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

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
