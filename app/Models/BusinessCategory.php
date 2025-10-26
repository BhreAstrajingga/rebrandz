<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BusinessCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (BusinessCategory $category): void {
            if (empty($category->slug)) {
                $source = $category->name ?: Str::random(6);
                $category->slug = static::generateUniqueSlug($source);
            }
        });

        static::updating(function (BusinessCategory $category): void {
            if (empty($category->slug)) {
                $source = $category->name ?: Str::random(6);
                $category->slug = static::generateUniqueSlug($source);
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
