@extends('layout')

@section('content')
    <a href="{{ route('posts.index') }}">← Back to Posts</a>

    <div class="card">
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->content }}</p>

        <a href="{{ route('posts.edit', $post) }}">Edit</a> |
        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>

    <h3>Comments ({{ $post->comments->count() }})</h3>

    @foreach($post->comments as $comment)
        <div class="card">
            <p>{{ $comment->comment }}</p>
        </div>
    @endforeach

    <h4>Add Comment</h4>
    <form action="{{ route('comments.store', $post) }}" method="POST">
        @csrf
        <textarea name="comment" rows="3" required></textarea>
        <button type="submit">Post Comment</button>
    </form>
@endsection
