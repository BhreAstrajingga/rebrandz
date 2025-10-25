<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class UserDashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user !== null && ((string) $user->user_type === 'customer');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createTenant')
                ->label('Create Tenant')
                ->color('primary')
                ->url(fn (): string => CreateTenant::getUrl(panel: 'user'))
                ->visible(fn (): bool => \Filament\Facades\Filament::getCurrentPanel()?->getId() === 'user'),
        ];
    }

    protected function getActions(): array
    {
        // Mirror header actions for compatibility with layouts expecting getActions()
        return $this->getHeaderActions();
    }
}
