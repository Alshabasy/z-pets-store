<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Z-Pets Admin',
            'email' => 'admin@zpets.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
