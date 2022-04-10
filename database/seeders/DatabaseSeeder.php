<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'padhilahm@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        User::factory(10)->create();
        Category::factory(3)->create();
        Post::factory(1000)->create();
    }
}
