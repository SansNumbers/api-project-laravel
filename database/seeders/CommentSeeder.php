<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Comment::create([
            'author' => 2,
            'post_id' => 2,
            'content' => 'First comment ever made'
        ]);
    }
}
