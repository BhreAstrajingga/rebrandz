<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Removed custom tenancy container binding.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user): bool {
            return (string) $user->user_type === 'admin';
        });

        // Register rate limiter for login attempts (used by 'throttle:login').
        RateLimiter::for('login', function (Request $request): array {
            $ipKey = (string) $request->ip();
            $email = (string) $request->input('email');

            $limits = [];
            if ($email !== '') {
                $limits[] = Limit::perMinute(5)->by($ipKey.'|'.$email);
            }

            $limits[] = Limit::perMinute(120)->by($ipKey);

            return $limits;
        });

        RateLimiter::for('blog-public', function (Request $request): array {
            $ip = (string) $request->ip();
            $ua = (string) $request->userAgent();

            $limits = [];
            $limits[] = Limit::perMinute(60)->by($ip);
            if ($ua === '') {
                $limits[] = Limit::perMinute(20)->by('no-ua|'.$ip);
            }

            return $limits;
        });

        // Only tenant owner (customer) may access member profiles
        Gate::define('access-member-profile', function (User $user): bool {
            return (string) $user->user_type === 'customer';
        });

        Gate::define('access-tenant', function (User $user, int $tenantId): bool {
            $tenant = \App\Models\Tenant::query()->select(['id', 'owner_id'])->find($tenantId);

            return (int) optional($tenant)->owner_id === (int) $user->id;
        });

        Gate::define('access-branch', function (User $user, int $branchId): bool {
            $tenantId = (int) ($user->tenant_id ?? 0);
            $branch = \App\Models\Branch::query()->select(['id', 'tenant_id'])->find($branchId);
            if ((int) optional($branch)->tenant_id !== $tenantId) {
                return false;
            }

            $tenant = \App\Models\Tenant::query()->select(['id', 'owner_id'])->find($tenantId);
            if ((int) optional($tenant)->owner_id === (int) $user->id) {
                return true;
            }

            return DB::table('branch_user')
                ->where('branch_id', $branchId)
                ->where('user_id', $user->id)
                ->exists();
        });
    }
}
