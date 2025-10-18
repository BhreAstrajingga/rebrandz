<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class TenantDashboard extends Page
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Tenant Dashboard';

    protected static ?string $slug = 'dashboard';

    protected string $view = 'filament.pages.tenant-dashboard';
}

