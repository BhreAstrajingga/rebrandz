<?php

namespace App\Observers;

use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        // Idempoten: hanya buat default branch jika belum ada satu pun
        if (! Branch::query()->where('tenant_id', $tenant->id)->exists()) {
            Branch::query()->create([
                'tenant_id' => $tenant->id,
                'name' => 'Default Branch',
                'code' => 'DEFAULT',
                'is_default' => true,
                'active' => true,
                'slug' => Str::slug('default-'.$tenant->id),
            ]);
        }
    }
}
