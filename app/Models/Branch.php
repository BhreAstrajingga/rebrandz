<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Branch extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'is_default',
        'active',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Branch $branch): void {
            if (empty($branch->slug)) {
                $branch->slug = static::generateUniqueSlug($branch->name ?: Str::random(6));
            }
        });

        static::updating(function (Branch $branch): void {
            if (empty($branch->slug)) {
                $branch->slug = static::generateUniqueSlug($branch->name ?: Str::random(6));
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
