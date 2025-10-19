<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class AppsSettings extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Settings';

    protected static \UnitEnum|string|null $navigationGroup = 'Apps';

    protected static ?string $slug = 'apps/settings';

    protected string $view = 'filament.pages.apps-settings';

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'user';
    }
}
