<?php

namespace App\Filament\Fx\Resources\FxUsers\Widgets;

use App\Models\FX\FxUser;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class UserFormWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.fx.resources.fx-users.widgets.user-form-widget';

    public ?int $userId = null;

    public ?array $data = [];

    public function mount(): void
    {
        // Bind a model so relationship() components can resolve
        $this->form->model($this->getFormModel());
        $this->form->fill();
    }

    protected $listeners = [
        'userSelected' => 'onUserSelected',
    ];

    public function onUserSelected($payload = null)
    {
        $this->userId = $payload['id'] ?? null;

        if ($this->userId) {
            $user = FxUser::find($this->userId);
            if ($user) {
                $this->data = $user->toArray();
                $this->data['roles'] = $user->roles()->pluck('id')->all();
                // Rebind the form to the selected user so relationship() works
                $this->form->model($user);
            }
        } else {
            $this->data = [];
            // Bind to a new model when nothing is selected
            $this->form->model(new FxUser);
        }

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(),

                TextInput::make('password')
                    ->required(fn (): bool => $this->userId === null)
                    ->minLength(6)
                    ->maxLength(255)
                    ->revealable()
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)),

                Select::make('user_type')
                    ->label('User Type')
                    ->options(fn (): array => User::query()
                        ->select('user_type')
                        ->whereNotNull('user_type')
                        ->distinct()
                        ->orderBy('user_type')
                        ->pluck('user_type', 'user_type')
                        ->toArray()
                    )
                    ->dehydrated()
                    ->visible(fn ($get) => $get('id') !== null)
                    ->default('fx')
                    ->required(),

                Select::make('roles')
                    ->label('Roles')
                    ->preload()
                    ->multiple()
                    ->relationship('roles', 'name', modifyQueryUsing: fn ($query) => $query->where('guard_name', 'web')),
            ])
            // Ensure a model is present for relationship-based components
            ->model($this->getFormModel())
            ->statePath('data');
    }

    protected function getFormModel(): FxUser
    {
        return $this->userId ? (FxUser::find($this->userId) ?: new FxUser) : new FxUser;
    }

    public function create(): void
    {
        $data = $this->form->getState();
        try {
            $user = FxUser::create($data);
            // Persist relationship-managed fields (e.g., roles)
            $this->form->model($user)->saveRelationships();

            $this->form->fill();
            Notification::make()
                ->title('Create Font')
                ->body('A user successfully created.')
                ->success()
                ->send();
            $this->dispatch('user-created');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating user')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function update(): void
    {
        $data = $this->form->getState();
        try {
            $user = FxUser::findOrFail($this->userId);
            $user->update($data);
            // Persist relationship-managed fields (e.g., roles)
            $this->form->model($user)->saveRelationships();

            $this->form->fill();
            $this->dispatch('user-updated');
            Notification::make()
                ->title('Font Update')
                ->body('A user successfully updated.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating user')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->userId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function delete(): void
    {
        $data = $this->form->getState();
        try {
            $user = FxUser::findOrFail($this->userId);
            $user->delete();
            $this->userId = null;
            $this->form->fill();
            $this->dispatch('user-deleted');
            Notification::make()
                ->title('Delete Service')
                ->body('A user successfully deleted.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error deleting user')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
