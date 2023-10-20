<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user()->id;
        $post = Post::where('id', $request->id)->first();

        if (!$post) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => "Not found"
                ]
            ])->setStatusCode(404));
        }

        if ($user !== $post->user_id) {
            return response()->json(['data' => ['errors' => 'unauthorized']], 401);
        }

        return $next($request);
    }
}
