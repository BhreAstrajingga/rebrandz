<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Component;

class Register extends BaseRegister
{
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
