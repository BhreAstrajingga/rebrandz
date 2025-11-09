<?php

namespace App\Filament\User\Pages;

use App\Models\Tenant;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class CreateTenant extends Page
{
    protected static ?string $navigationLabel = 'Create Tenant';

    public function getTitle(): string
    {
        return 'Create Tenant';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Name')->required()->autofocus(),
            TextInput::make('domain')->label('Domain')->required(),
        ])->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $tenant = Tenant::query()->create([
            'name' => (string) ($data['name'] ?? ''),
            'domain' => (string) ($data['domain'] ?? ''),
        ]);

        Notification::make()->title('Tenant created')->success()->send();

        $this->redirect(TenantOverview::getUrl(panel: 'user'));
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')->label('Create')->submit('create'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
