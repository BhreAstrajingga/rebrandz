<?php

namespace App\Models\FX;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class FxUser extends Authenticatable implements FilamentUser
{
    use HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected string $guard_name = 'web';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('fx', function ($builder): void {
            $builder->whereIn('user_type', ['fx', 'system', 'admin', 'manager', 'staff']);
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

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();
        $type = $this->user_type;

        if ($panelId === 'admin') {
            return in_array($type, ['system', 'admin'], true);
        }

        if ($panelId === 'fx') {
            return in_array($type, ['system', 'admin', 'manager', 'staff', 'fx'], true);
        }

        if ($panelId === 'user') {
            return in_array($type, ['customer', 'system', 'admin'], true);
        }

        if ($panelId === 'tenant') {
            return in_array($type, ['customer', 'system', 'admin'], true);
        }

        return false;
    }
}
