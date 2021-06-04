<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create([
          'title' => 'Category1',
          'description' => 'This is 1st ever made category.'
        ]);

        \App\Models\Category::create([
          'title' => 'Category2',
          'description' => 'This is 2nd ever made category.'
        ]);
      }
}
