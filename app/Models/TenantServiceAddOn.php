<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantServiceAddOn extends Model
{
    protected $fillable = [
        'tenant_id',
        'service_id',
        'add_on_id',
        'activated_at',
        'status',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function addOn(): BelongsTo
    {
        return $this->belongsTo(AddOn::class, 'add_on_id');
    }
}
