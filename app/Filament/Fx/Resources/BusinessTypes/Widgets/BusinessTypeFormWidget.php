<?php

namespace App\Filament\Fx\Resources\BusinessTypes\Widgets;

use App\Models\FX\BusinessType;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Support\Arr;

class BusinessTypeFormWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions { callMountedAction as filamentCallMountedAction; }
    use InteractsWithForms;

    protected string $view = 'filament.fx.resources.business-types.widgets.business-type-form-widget';

    public ?int $businessTypeId = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected $listeners = [
        'businessTypeSelected' => 'onBusinessTypeSelected',
    ];

    public function onBusinessTypeSelected($payload = null)
    {
        $this->businessTypeId = is_array($payload) ? ($payload['id'] ?? null) : null;

        if ($this->businessTypeId) {
            $businessType = BusinessType::find($this->businessTypeId);
            if ($businessType) {
                $this->data = $businessType->toArray();
                $this->form->model($businessType);
            }
        } else {
            $this->data = [];
            // Rebind to a fresh model so relationship-based components work
            $this->form->model(new BusinessType);
        }

        // For new records, call fill() without overriding state so Repeater defaultItems apply
        if ($this->businessTypeId) {
            $this->form->fill($this->data);
        } else {
            $this->form->fill();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u'])
                    ->helperText('Give unique name. Only letters, numbers, spaces, hyphens, and underscores are allowed.'),

                Repeater::make('details')
                    ->relationship('details')
                    ->table([
                        TableColumn::make('description')->width('60%'),
                        TableColumn::make('quantity'),
                        TableColumn::make('price'),
                    ])
                    ->schema([
                        RichEditor::make('description')
                            ->extraInputAttributes([
                                'style' => 'resize: vertical;min-height: 2rem; max-height: auto; overflow-y: auto;',
                            ])
                            ->toolbarButtons(['bulletList', 'orderedList'])
                            ->maxLength(500)
                            ->dehydrateStateUsing(function ($state) {
                                // Pastikan state adalah string sebelum disimpan
                                return is_array($state) ? json_encode($state) : $state;
                            })
                            ->afterStateHydrated(function (RichEditor $component, $state) {
                                if (is_string($state)) {
                                    $component->state($state);
                                }
                            }),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                    ])
                    ->minItems(1)
                    ->columnSpanFull()
                    ->label('Business Type Details')
                    ->helperText('Add one or more details for this Business Type.'),
            ])
            ->model($this->getFormModel())
            ->statePath('data');
    }

    protected function getFormModel(): BusinessType
    {
        if ($this->businessTypeId) {
            return BusinessType::find($this->businessTypeId) ?? new BusinessType;
        }

        return new BusinessType;
    }

    public function create(): void
    {
        $data = $this->form->getState();
        try {
            $attributes = Arr::except($data, ['details']);
            $businessType = new BusinessType;
            $businessType->fill($attributes);
            $businessType->save();

            $this->businessTypeId = $businessType->id;
            $this->form->model($businessType)->saveRelationships();
            // Clear any mounted actions (e.g., from Repeater add/delete) to avoid leftover redirects/errors
            $this->unmountAction();
            $this->form->fill();
            Notification::make()
                ->title('Business Type created successfully.')
                ->success()
                ->send();
            $this->dispatch('business-type-created');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to create Business Type.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->businessTypeId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function update(): void
    {
        $data = $this->form->getState();
        try {
            $businessType = BusinessType::find($this->businessTypeId);
            if ($businessType) {
                $attributes = Arr::except($data, ['details']);
                $businessType->update($attributes);
                $this->form->model($businessType)->saveRelationships();
                // Clear any mounted actions (e.g., from Repeater add/delete) to avoid leftover redirects/errors
                $this->unmountAction();
                Notification::make()
                    ->title('Business Type updated successfully.')
                    ->success()
                    ->send();
                $this->dispatch('business-type-updated');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to update Business Type.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function delete(): void
    {
        try {
            $businessType = BusinessType::find($this->businessTypeId);
            if ($businessType) {
                $businessType->delete();
                $this->form->fill();
                Notification::make()
                    ->title('Business Type deleted successfully.')
                    ->success()
                    ->send();
                $this->dispatch('business-type-deleted');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to delete Business Type.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Safely handle mounted actions to prevent redirect-related errors from nested component actions.
     *
     * @param  array<string, mixed>  $arguments
     */
    public function callMountedAction(array $arguments = []): mixed
    {
        try {
            return $this->filamentCallMountedAction($arguments);
        } catch (\Throwable $e) {
            // Clear any mounted actions and swallow errors; data operations are already handled.
            $this->unmountAction(canCancelParentActions: false);

            return null;
        }
    }
}
