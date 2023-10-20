<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'email' => 'rafi@gmail.com',
            'username' => 'rafi',
            'password' => Hash::make('rafi'),
        ]);
        User::create([
            'email' => 'akmal@gmail.com',
            'username' => 'akmal',
            'password' => Hash::make('akmal'),
        ]);
    }
    // Post::create([
    //     'title' => 'Post two',
    //     'news_content' => fake()->paragraphs(10, 15),
    //     'user_id' => 1
    // ]);
    // Post::create([
    //     'title' => 'Post Three',
    //     'news_content' => fake()->paragraphs(14, 17),
    //     'user_id' => 2
    // ]);
}
