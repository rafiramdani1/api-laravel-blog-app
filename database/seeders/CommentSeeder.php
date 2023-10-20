<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'user_id' => 1,
            'post_id' => 4,
            'comments_content' => 'Keren lau ngab'
        ]);
        Comment::create([
            'user_id' => 2,
            'post_id' => 4,
            'comments_content' => 'thank ngab'
        ]);
        Comment::create([
            'user_id' => 1,
            'post_id' => 4,
            'comments_content' => 'ok ngab'
        ]);
        Comment::create([
            'user_id' => 2,
            'post_id' => 6,
            'comments_content' => 'gile lu ngab'
        ]);
        Comment::create([
            'user_id' => 2,
            'post_id' => 6,
            'comments_content' => 'elu yg gila'
        ]);
    }
}
