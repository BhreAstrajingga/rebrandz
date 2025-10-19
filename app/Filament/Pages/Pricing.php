<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class Pricing extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Pricing';

    protected static \UnitEnum|string|null $navigationGroup = 'Pricing';

    protected static ?string $slug = 'pricing';

    protected string $view = 'filament.pages.pricing';

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'user';
    }
}
