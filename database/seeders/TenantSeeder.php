<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'domain' => null,
            ],
        ];

        foreach ($tenants as $data) {
            Tenant::query()->updateOrCreate(
                ['slug' => Str::slug($data['slug'])],
                [
                    'name' => $data['name'],
                    'domain' => $data['domain'],
                ],
            );
        }
    }
}

