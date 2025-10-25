<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class SubscriberHome extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $slug = 'dashboard';

    protected string $view = 'filament.pages.subscriber-home';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createTenant')
                ->label('Create Tenant')
                ->color('primary')
                ->url(fn (): string => \App\Filament\Pages\CreateTenant::getUrl(panel: 'user')),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'user';
    }
}
