@extends('layouts.app')

@section('content')

    <header class="intro-header" style="background-color: #ccc; margin-top: 0px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1>{{ $post->title }}</h1>
                        <span class="meta">Posted by{{ $post->user->name }} on
                        {{ \Illuminate\Support\Carbon::parse($post->publication_date)->toFormattedDateString() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="px-3">
        {{ $post->description }}
    </section>

@endsection
