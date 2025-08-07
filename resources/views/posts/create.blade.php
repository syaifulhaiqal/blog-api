@extends('layout')

@section('post')
    <h2>Create New Post</h2>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Description</label>
        <textarea name="description" rows="5" required></textarea>

        <button type="submit">Save Post</button>
    </form>
@endsection
