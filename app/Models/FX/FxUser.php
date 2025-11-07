<?php

namespace App\Models\FX;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FxUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'user_type',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('fx', function ($builder): void {
            $builder->where('user_type', 'fx');
        });

        // Set defaults and ensure slug on create
        static::creating(function (FxUser $fxUser): void {
            $fxUser->user_type = 'fx';
            $fxUser->email_verified_at = now();

            if (empty($fxUser->slug)) {
                $fxUser->slug = static::generateUniqueSlug($fxUser->name ?: Str::random(6));
            }
        });

        // Ensure slug present on update if missing
        static::updating(function (FxUser $fxUser): void {
            if (empty($fxUser->slug)) {
                $fxUser->slug = static::generateUniqueSlug($fxUser->name ?: Str::random(6));
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
