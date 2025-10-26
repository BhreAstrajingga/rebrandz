<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantBranchDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Create a demo tenant (owner assigned below)
        $tenant = Tenant::query()->firstOrCreate(
            ['slug' => 'demo-tenant'],
            [
                'name' => 'Demo Tenant',
                'domain' => null,
                'owner_id' => null,
            ],
        );

        // Ensure default branch exists (observer should create it). Fallback if needed
        $defaultBranch = Branch::query()->firstOrCreate(
            ['tenant_id' => $tenant->id, 'is_default' => true],
            [
                'name' => 'Default Branch',
                'code' => 'DEFAULT',
                'active' => true,
                'slug' => 'default-'.$tenant->id,
            ],
        );

        // 2) Create tenant owner (global role: customer)
        $owner = User::query()->firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Tenant Owner',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
            ],
        );

        // Set tenant owner_id if missing
        if (! $tenant->owner_id) {
            $tenant->owner_id = $owner->id;
            $tenant->save();
        }

        // Ensure pivot membership at tenant level
        $tenant->members()->syncWithoutDetaching([
            $owner->id => ['status' => 'active', 'assigned_by' => $owner->id],
        ]);

        // 3) Create a branch member user (global role: branch_member)
        $staff = User::query()->firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Branch Staff',
                'password' => Hash::make('password'),
                'user_type' => 'branch_member',
            ],
        );

        // Add staff to tenant members and assign to default branch
        $tenant->members()->syncWithoutDetaching([
            $staff->id => ['status' => 'active', 'assigned_by' => $owner->id],
        ]);

        $defaultBranch->members()->syncWithoutDetaching([$staff->id => ['status' => 'active']]);
    }
}
