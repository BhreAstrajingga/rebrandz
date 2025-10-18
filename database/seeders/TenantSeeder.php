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
                'name' => 'Acme, Inc.',
                'slug' => 'acme',
                'domain' => 'acme.' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'app.test'),
            ],
            [
                'name' => 'Bravo LLC',
                'slug' => 'bravo',
                'domain' => 'bravo.' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'app.test'),
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

