@extends('layout')

@section('post')
    <h2>Edit Post</h2>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ $post->title }}" required>

        <label>Description</label>
        <textarea name="description" rows="5" required>{{ $post->content }}</textarea>

        <button type="submit">Update</button>
    </form>
@endsection
