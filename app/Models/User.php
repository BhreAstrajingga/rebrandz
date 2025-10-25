<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'user_type', // admin, customer
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (empty($user->slug)) {
                $user->slug = static::generateUniqueSlug($user->name ?: Str::random(6));
            }
        });

        static::updating(function (User $user): void {
            // Regenerate when name changed or slug missing
            if ($user->isDirty('name') || empty($user->slug)) {
                $user->slug = static::generateUniqueSlug($user->name ?: Str::random(6));
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

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();
        $type = (string) $this->user_type;

        if ($panelId === 'admin') {
            return in_array($type, ['system', 'admin'], true);
        }

        if ($panelId === 'user') {
            return $type === 'customer';
        }

        if ($panelId === 'tenant') {
            return in_array($type, ['customer', 'system', 'admin'], true);
        }

        return false;
    }

    public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        $type = (string) $this->user_type;

        if (in_array($type, ['system', 'admin'], true)) {
            return true;
        }

        return (int) $this->tenant_id === (int) $tenant->getKey();
    }

    public function getTenants(Panel $panel): array|Collection
    {
        $type = (string) $this->user_type;

        if (in_array($type, ['system', 'admin'], true)) {
            return \App\Models\Tenant::query()->orderBy('name')->get();
        }

        if ($this->tenant_id) {
            $tenant = \App\Models\Tenant::find($this->tenant_id);

            return $tenant ? collect([$tenant]) : collect();
        }

        return collect();
    }
}
