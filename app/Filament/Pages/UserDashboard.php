<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class UserDashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createTenant')
                ->label('Create Tenant')
                ->color('primary')
                ->url(fn (): string => CreateTenant::getUrl(panel: 'user')),
        ];
    }

    protected function getActions(): array
    {
        // Mirror header actions for compatibility with layouts expecting getActions()
        return $this->getHeaderActions();
    }
}
