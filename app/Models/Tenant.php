<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'domain',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant): void {
            if (empty($tenant->slug)) {
                $tenant->slug = static::generateUniqueSlug($tenant->name ?: Str::random(6));
            }
        });
        static::updating(function (Tenant $tenant): void {
            if ($tenant->isDirty('name') || empty($tenant->slug)) {
                $tenant->slug = static::generateUniqueSlug($tenant->name ?: Str::random(6));
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
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
