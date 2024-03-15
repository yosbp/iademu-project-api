<?php

namespace Database\Seeders;

use App\Models\Provider;
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

        // Create providers
        Provider::create(['name' => 'COMERCIAL JAMYED 2016 C.A.', 'rif' => 'J-123456789', 'address' => 'Calle 1, casa 2', 'phone' => '0412-1234567']);
    }
}
