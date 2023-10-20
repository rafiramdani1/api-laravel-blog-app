<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function getPosts()
    {
        $posts = Post::latest()->with(['user:id,username', 'comments'])->get();

        if (!$posts) {
            throw new HttpResponseException(response([
                "message" => "Data Posts Not Found"
            ]));
        }

        return PostResource::collection($posts);
    }

    public function getPostBySlug($slug)
    {
        $post = Post::with('user:id,username', 'comments')->where('slug', $slug)->first();

        if (!$post) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => "Not found"
                ]
            ])->setStatusCode(404));
        }

        return new PostResource($post);
    }

    public function getPostsByUserId(Request $request)
    {
        $posts = Post::latest()->with('user:id,username', 'comments')->where('user_id', $request->id)->get();
        if ($posts->isEmpty()) {
            throw new HttpResponseException(response()->json([
                "message" => "Post Not found",
                "data" => []
            ]));
        }

        return PostResource::collection($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated =  $request->validate([
            'title' => 'required|max:255|unique:posts',
            'slug' => 'required|unique:posts',
            'news_content' => 'required',
        ]);

        $validated['user_id'] = Auth::user()->id;
        $validated['excerpt'] = Str::limit($validated['news_content'], 100);

        $post = Post::create($validated);

        return (new PostResource($post->loadMissing('user:id,username')))->additional([
            'message' => 'post created successfully',
            'created_at' => $post->created_at->format('Y-m-d H:i:s')
        ])->response()->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::where('id', $id)->first();

        if (!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "Not Found"
                ]
            ], 400));
        }

        $data = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required',
            'news_content' => 'required',
        ]);

        $titleDuplicate = Post::where('title', $data['title'])->first();
        if ($titleDuplicate && $data['title'] !== $post->title) {
            throw new HttpResponseException(response([
                "errors" => [
                    'message' => "title already exists"
                ]
            ], 400));
        }

        $post->fill($data);
        $post->save();

        return (new PostResource($post))->additional([
            'message' => 'post updated successfully',
            'updated_at' => $post->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::where('id', $id)->first();

        if (!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "Bad request"
                ]
            ], 400));
        }

        $post->delete();
        return response()->json([
            "data" => [
                "message" => "post deleted successfully"
            ]
        ], 200);
    }
}
