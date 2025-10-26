<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class TenantOverview extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'My Tenant';

    // protected static \UnitEnum|string|null $navigationGroup = 'Tenant';

    protected static ?string $slug = 'tenant/overview';

    protected string $view = 'filament.pages.tenant-overview';

    public function mount(): void
    {
        $user = Filament::auth()->user();
        $tenantId = (int) ($user?->tenant_id ?? 0);
        if ($tenantId === 0 || ! Gate::allows('access-tenant', $tenantId)) {
            abort(403);
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();
        if (Filament::getCurrentPanel()?->getId() !== 'user' || ! $user?->tenant_id) {
            return false;
        }

        return Gate::allows('access-tenant', (int) $user->tenant_id);
    }
}
