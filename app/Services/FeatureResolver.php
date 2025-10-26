<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\UserSubscription;
use Illuminate\Support\Collection;

class FeatureResolver
{
    /**
     * Resolve activated features for a tenant+service from plan and add-ons.
     */
    public static function forTenantService(Tenant $tenant, int $serviceId): Collection
    {
        $features = collect();

        $subscription = UserSubscription::query()
            ->where('tenant_id', $tenant->getKey())
            ->where('service_id', $serviceId)
            ->where('status', 'ACTIVE')
            ->latest('start_date')
            ->first();

        if ($subscription && is_array($subscription->plan?->features ?? null)) {
            $features = $features->merge($subscription->plan->features);
        }

        $addons = $tenant->serviceAddOns()
            ->where('service_id', $serviceId)
            ->where('status', 'active')
            ->pluck('addOn.code');

        return $features->merge($addons)->unique()->values();
    }
}
