<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\CurrentTenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __construct(private CurrentTenant $currentTenant) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = (string) $request->getHost();

        $baseHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

        $subdomain = null;
        if (str_ends_with($host, '.' . $baseHost)) {
            $subdomain = substr($host, 0, -1 * (strlen($baseHost) + 1));
        } elseif ($host !== $baseHost && ! filter_var($host, FILTER_VALIDATE_IP)) {
            $subdomain = explode('.', $host)[0] ?? null;
        }

        $tenant = null;
        if ($subdomain && ! in_array($subdomain, ['www', 'admin'])) {
            $tenant = Tenant::query()
                ->where('domain', $host)
                ->orWhere('slug', $subdomain)
                ->first();
        }

        $this->currentTenant->set($tenant);

        return $next($request);
    }
}

