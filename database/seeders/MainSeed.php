<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create user
        User::create(['name' => 'Admin', 'lastname' => 'Test', 'email' => 'admin@test.com', 'password' => 123456, 'username' => 'admin']);
    }
}
