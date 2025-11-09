<?php

namespace App\Filament\User\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class SupportTickets extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Support Tickets';

    protected static \UnitEnum|string|null $navigationGroup = 'Support';

    protected static ?string $slug = 'support/tickets';

    protected string $view = 'filament.pages.support-tickets';

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'user';
    }
}
