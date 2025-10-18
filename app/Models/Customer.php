<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Customer extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'user_type',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('customer', function ($builder): void {
            $builder->where('user_type', 'customer');
        });

        // Set defaults and ensure slug on create
        static::creating(function (Customer $customer): void {
            $customer->user_type = 'customer';
            $customer->email_verified_at = now();

            if (empty($customer->slug)) {
                $customer->slug = static::generateUniqueSlug($customer->name ?: Str::random(6));
            }
        });

        // Ensure slug present on update if missing
        static::updating(function (Customer $customer): void {
            if (empty($customer->slug)) {
                $customer->slug = static::generateUniqueSlug($customer->name ?: Str::random(6));
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
