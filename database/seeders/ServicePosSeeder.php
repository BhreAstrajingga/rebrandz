<?php

namespace Database\Seeders;

use App\Models\AddOn;
use App\Models\Service;
use App\Models\ServicePlan;
use Illuminate\Database\Seeder;

class ServicePosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1) POS Service
        $service = Service::query()->firstOrCreate(
            ['slug' => 'pos'],
            [
                'name' => 'Point of Sale',
                'type' => 'pos',
                'description' => 'POS service for tenants (single provider).',
                'is_active' => true,
            ],
        );

        // 2) Plans (Basic, Pro, Enterprise) with simple features
        $plans = [
            ['name' => 'Basic', 'price_monthly' => 0, 'price_yearly' => 0, 'features' => ['CorePOS']],
            ['name' => 'Pro', 'price_monthly' => 99000, 'price_yearly' => 990000, 'features' => ['CorePOS', 'Reports']],
            ['name' => 'Enterprise', 'price_monthly' => 199000, 'price_yearly' => 1990000, 'features' => ['CorePOS', 'Reports', 'API']],
        ];

        foreach ($plans as $p) {
            ServicePlan::query()->firstOrCreate(
                ['service_id' => $service->id, 'name' => $p['name']],
                [
                    'price' => $p['price_monthly'],
                    'interval' => 'month',
                    'duration' => 1,
                    'features' => $p['features'],
                    'is_active' => true,
                ],
            );
            ServicePlan::query()->firstOrCreate(
                ['service_id' => $service->id, 'name' => $p['name'].' Yearly'],
                [
                    'price' => $p['price_yearly'],
                    'interval' => 'year',
                    'duration' => 1,
                    'features' => $p['features'],
                    'is_active' => true,
                ],
            );
        }

        // 3) Example AddOns
        $addons = [
            ['code' => 'MultiBranch', 'name' => 'Multi Branch', 'description' => 'Enable multiple branches under tenant.', 'price_one_time' => 0, 'status' => 'active'],
            ['code' => 'MultiWarehouse', 'name' => 'Multi Warehouse', 'description' => 'Enable multiple warehouses per tenant.', 'price_one_time' => 0, 'status' => 'active'],
            ['code' => 'HR', 'name' => 'HR', 'description' => 'Basic HR capabilities for POS.', 'price_one_time' => 0, 'status' => 'active'],
        ];
        foreach ($addons as $a) {
            AddOn::query()->firstOrCreate(['code' => $a['code']], $a);
        }
    }
}
