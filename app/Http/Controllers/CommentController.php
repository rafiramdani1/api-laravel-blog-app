<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function getCommentByPostId(Request $request, $id)
    {

        $comments = Comment::with('user:id,username', 'post:id,title')->where('post_id', $id)->get();

        if ($comments->isEmpty()) {
            throw new HttpResponseException(response([
                "data" => [],
                "message" => "No Comment"
            ]));
        }

        return CommentResource::collection($comments);
    }

    public function create(Request $request, $id): CommentResource
    {

        $post = Post::where('id', $id)->first();

        if (!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "Bad request"
                ]
            ], 400));
        }

        $data = $request->validate([
            'comments_content' => 'required'
        ]);

        $data['user_id'] = Auth::user()->id;
        $data['post_id'] = $post->id;

        $comment = Comment::create($data);
        return (new CommentResource($comment));
    }

    public function delete(Request $request, $id)
    {
        $comment = Comment::where('id', $id)->first();

        if (!$comment) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "Bad request"
                ]
            ], 400));
        }

        if ($comment->user_id !== Auth::user()->id) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "unauthenticate"
                ]
            ], 401));
        }

        $comment->delete();
    }
}
