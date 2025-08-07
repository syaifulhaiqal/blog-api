@extends('layout')

@section('content')
    <a href="{{ route('posts.create') }}">
        <button>Create New Post</button>
    </a>

    <h2>All Posts</h2>

    @foreach($posts as $post)
        <div class="card">
            <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
            <p>{{ Str::limit($post->content, 100) }}</p>
        </div>
    @endforeach
@endsection
