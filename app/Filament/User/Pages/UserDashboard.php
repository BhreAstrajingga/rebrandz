<?php

namespace App\Filament\User\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class UserDashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        if ($user === null) {
            return false;
        }

        // Allow Customer, and ensure System/Admin and Super Admin can access all panels.
        if ((string) $user->user_type === 'customer') {
            return true;
        }

        if (in_array((string) $user->user_type, ['system', 'admin'], true)) {
            return true;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
            return true;
        }

        return false;
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
