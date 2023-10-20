<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);

Route::get('/posts', [PostController::class, 'getPosts']);
Route::get('/posts/{slug}', [PostController::class, 'getPostBySlug']);
Route::get('/posts/user/{id}', [PostController::class, 'getPostsByUserId']);

Route::get('/comments/{post_id}', [CommentController::class, 'getCommentByPostId']);

Route::middleware(['auth:sanctum'])->group(function () {
  Route::post('/posts', [PostController::class, 'store']);
  Route::put('/posts/{id}', [PostController::class, 'update'])->middleware('post.update');
  Route::delete('/posts/{id}', [PostController::class, 'delete'])->middleware('post.update');

  Route::get('/user/profile', [UserController::class, 'profile']);

  Route::post('comments/{post_id}', [CommentController::class, 'create']);
  Route::delete('comments/{id}', [CommentController::class, 'delete']);

  Route::delete('/logout', [AuthenticationController::class, 'logout']);
});
