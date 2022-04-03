<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(User::class)->truncate();

        app(User::class)->create([
            'name' => 'test@test.com',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('test@test.com'),
            'remember_token' => Str::random(10),
        ]);
    }
}
