<?php

namespace App\Filament\Pages\PasswordReset;

use Filament\Actions\Action;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Filament\Facades\Filament;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function mount(): void
    {
        Filament::setCurrentPanel('user');

        parent::mount();
    }

    public function loginAction(): Action
    {
        return parent::loginAction()
            ->url(route('login'));
    }
}
