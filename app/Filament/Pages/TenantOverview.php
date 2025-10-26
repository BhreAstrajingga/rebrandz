<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class TenantOverview extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'My Tenant';

    // protected static \UnitEnum|string|null $navigationGroup = 'Tenant';

    protected static ?string $slug = 'tenant/overview';

    protected string $view = 'filament.pages.tenant-overview';

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return Filament::getCurrentPanel()?->getId() === 'user' && (bool) ($user?->tenant_id);
    }
}
