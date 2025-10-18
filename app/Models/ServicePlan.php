<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePlan extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'price',
        'interval',
        'duration',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Plan bisa dipakai oleh banyak subscription user
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }
}
