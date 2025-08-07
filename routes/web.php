<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('posts', 'PostController');

// Route::get('posts', "PostController@index"); // List Posts
// Route::post('posts', "PostController@store"); // Create Post
// Route::get('posts/{id}', "PostController@show"); // Detail of Post
// Route::put('posts/{id}', "PostController@update"); // Update Post
// Route::delete('posts/{id}', "PostController@destroy"); // Delete Post

// Route::get('posts',[PostController::class,'index'])->name('index');

// <?php

// use App\Http\Controllers\PostController;
// use Illuminate\Support\Facades\Route;

// Route::get('posts', "PostController@index"); // List Posts
// Route::post('posts', "PostController@store"); // Create Post
// Route::get('posts/{id}', "PostController@show"); // Detail of Post
// Route::put('posts/{id}', "PostController@update"); // Update Post
// Route::delete('posts/{id}', "PostController@destroy"); // Delete Post

// Route::get('posts',[PostController::class,'index'])->name('index');
// Route::post('posts',[PostController::class,'store'])->name('store');
// Route::get('posts/{id}',[PostController::class,'show'])->name('show');
// Route::put('posts/{id}',[PostController::class,'update'])->name('update');
// Route::delete('posts/{id}',[PostController::class,'destroy'])->name('destroy');

// Route::resource('posts',[PostController::class]);
Route::get('/posts/list', [PostController::class, 'list'])->name('posts.list');
