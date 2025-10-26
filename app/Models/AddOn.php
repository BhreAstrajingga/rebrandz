<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AddOn extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'price_one_time',
        'status',
    ];

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_service_add_ons', 'add_on_id', 'tenant_id')
            ->withPivot(['service_id', 'activated_at', 'status'])
            ->withTimestamps();
    }
}
