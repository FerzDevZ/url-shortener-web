<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@urlshortener.test',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);
    }
}
