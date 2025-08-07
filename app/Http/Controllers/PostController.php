<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            // All Posts
        $posts = Post::all();

        // Return Json Response
        return response()->json([
            'posts' => $posts
        ], 200);
    }

    public function list()
    {
        return view('posts.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        try {
        $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

        // Create Post
        Post::create([
            'name' => $request->name,
            'image' => $imageName,
            'description' => $request->description
        ]);

        // Save Image in Storage folder
        Storage::disk('public')->put($imageName, file_get_contents($request->image));

        // Return Json Response
        return response()->json([
            'message' => "Post successfully created."
        ], 200);
    } catch (\Exception $e) {
        // Return Json Response
       
        return response()->json([
            'message' => "Something went really wrong!"
        ], 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Post Detail
    $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found.'
            ], 404);
        }

        // Return Json Response
        return response()->json([
            'post' => $post
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            try {
            // Find Post
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Post Not Found.'
                ], 404);
            }

            $post->name = $request->name;
            $post->description = $request->description;

            if ($request->image) {
                // Public storage
                $storage = Storage::disk('public');

                // Old image delete
                if ($storage->exists($post->image))
                    $storage->delete($post->image);

                // Image name
                $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                $post->image = $imageName;

                // Image save in public folder
                $storage->put($imageName, file_get_contents($request->image));
            }

            // Update Post
            $post->save();

            // Return Json Response
            return response()->json([
                'message' => "Post successfully updated."
            ], 200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            // Post Detail
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found.'
            ], 404);
        }

        // Public storage
        $storage = Storage::disk('public');

        // Image delete
        if ($storage->exists($post->image))
            $storage->delete($post->image);

        // Delete Post
        $post->delete();

        // Return Json Response
        return response()->json([
            'message' => "Post successfully deleted."
        ], 200);
        }
}
