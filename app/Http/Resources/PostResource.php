<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'news_content' => $this->news_content,
            'excerpt' => $this->excerpt,
            'user_id' => $this->whenLoaded('user'),
            'created_at' => $this->created_at,
            'comments' => $this->whenLoaded('comments', function () {
                return $this->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comments_content' => $comment->comments_content,
                        'created_at' => $comment->created_at
                    ];
                });
            }),
        ];
    }
}
