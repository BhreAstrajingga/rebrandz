<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'is_active',
    ];

    public function plans()
    {
        return $this->hasMany(ServicePlan::class);
    }

    // Satu service bisa punya banyak subscription
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Service $service): void {
            if (empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name ?: Str::random(6));
            }
        });

        static::updating(function (Service $service): void {
            // Regenerate when name changed or slug missing
            if ($service->isDirty('name') || empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name ?: Str::random(6));
            }
        });
    }

    protected static function generateUniqueSlug(string $source): string
    {
        $base = Str::slug($source);
        if ($base === '') {
            $base = strtolower(Str::random(6));
        }
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
