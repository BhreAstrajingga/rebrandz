<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one tenant exists for customer linking
        $tenantId = \DB::table('tenants')->where('slug', 'laravel')->value('id');

        // Admin user
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ],
        );

        // Customer user linked to 'laravel' tenant
        User::query()->updateOrCreate(
            ['email' => 'tenant@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
                'tenant_id' => $tenantId,
            ],
        );
    }
}
