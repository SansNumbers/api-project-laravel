<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'login' => 'adminUser',
            'password' => bcrypt('admin'),
            'email' => 'admin@admin.com',
            'name' => 'admin',
            'role' => 'admin'
        ]);
        \App\Models\User::create([
            'login' => 'usertest',
            'password' => bcrypt('usertest'),
            'email' => 'usertest@gmail.com',
            'name' => 'Username',
            'role' => 'user'
        ]);
        \App\Models\User::create([
            'login' => 'Somerset',
            'password' => bcrypt('qwerty123'),
            'email' => 'kreig.antipin@gmail.com',
            'name' => 'Igor Antypin',
            'role' => 'admin'
        ]);
    }
}
