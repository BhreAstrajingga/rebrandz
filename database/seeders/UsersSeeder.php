<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ],
        );

        // Customer user (no tenancy)
        User::query()->updateOrCreate(
            ['email' => 'tenant@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
            ],
        );
    }
}
