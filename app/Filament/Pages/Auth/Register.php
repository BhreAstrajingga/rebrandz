<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{
    public function mount(): void
    {
        // Ensure we are using the user panel context for registration
        Filament::setCurrentPanel('user');

        parent::mount();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getUserTypeFormComponent(),
            ]);
    }

    protected function getUserTypeFormComponent(): Component
    {
        return Hidden::make('user_type')
            ->default('customer');
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label(__('filament-panels::auth/pages/register.actions.login.label'))
            ->url(route('login'));
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString(__('filament-panels::auth/pages/register.actions.login.before').' '.$this->loginAction->toHtml());
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // Ensure user_type is always set to customer
        $data['user_type'] = 'customer';

        return $data;
    }
}
