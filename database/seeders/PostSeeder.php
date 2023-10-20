<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 10; $i++) {
            Post::create([
                'title' => fake()->sentence(),
                'slug' => fake()->sentence(),
                'excerpt' => fake()->sentence(),
                'news_content' => fake()->sentence(),
                'user_id' => mt_rand(1, 2)
            ]);
        }
        // for ($i = 0; $i < 1000; $i++) {
        //     Post::create([
        //         'title' => fake()->sentence(),
        //         'slug' => fake()->sentence(),
        //         'excerpt' => fake()->sentence(),
        //         'news_content' => fake()->sentence(),
        //         'user_id' => 2
        //     ]);
        // }
    }
}
