<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $categories = new \stdClass();
        $categories->value = 1;
        \App\Models\Post::create([
            'author' => 1,
            'title' => 'First ever post',
            'content' => 'This is first ever post created',
            'categories' => [$categories],
            'status' => 'active'
        ]);

        $categories->value = 2;
        \App\Models\Post::create([
            'author' => 1,
            'title' => 'Second post ever made',
            'content' => 'This is second post created',
            'categories' => [$categories],
            'status' => 'active'
        ]);

        \App\Models\Post::create([
          'author' => 1,
          'title' => 'Third post',
          'content' => 'This is third post created',
          'categories' => [$categories]
      ]);
    }
}
