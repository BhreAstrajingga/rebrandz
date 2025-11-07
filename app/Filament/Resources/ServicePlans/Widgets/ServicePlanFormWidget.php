<?php

namespace App\Filament\Resources\ServicePlans\Widgets;

use App\Models\ServicePlan;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Log;

class ServicePlanFormWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected string $view = 'filament.resources.service-plans.widgets.service-plan-form-widget';
    public ?int $servicePlanId = null;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected $listeners = [
        'planSelected' => 'setSelectedPlan',
        'plan-created' => 'refreshPlan',
        'plan-updated' => 'refreshPlan',
        'plan-deleted' => 'refreshPlan',
    ];

    public function setSelectedPlan($payload = null)
    {
        $this->servicePlanId = $payload['id'] ?? null;

        if ($this->servicePlanId) {
            $service = ServicePlan::find($this->servicePlanId);
            if ($service) {
                $this->data = $service->toArray();
            }
        } else {
            $this->data = [];
        }

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
                    ->label('Service')
                    ->options(
                        \App\Models\Service::query()
                            ->where('is_active', true) // opsional
                            ->pluck('name', 'id')      // kolom label dan key
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                TextInput::make('price')
                    ->required()
                    ->numeric(),

                Select::make('interval')
                    ->label('Interval')
                    ->options([
                        'daily'   => 'Daily (Free)',
                        'monthly' => 'Monthly',
                        'yearly'  => 'Yearly',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'daily') {
                            $set('duration', 7);
                        } elseif ($state === 'monthly') {
                            $set('duration', 1);
                        } else { // yearly
                            $set('duration', 1);
                        }
                    }),

                // Input duration
                TextInput::make('duration')
                    ->label('Durasi')
                    ->numeric()
                    ->reactive()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(fn($get) => match ($get('interval')) {
                        'daily'   => 7,
                        'monthly' => 1,
                        'yearly'  => 3,
                        default   => 1,
                    })
                    ->readonly(fn($get) => in_array($get('interval'), ['daily', 'monthly'])),

                Textarea::make('features')
                    ->label('Fitur')
                    ->helperText('Pisahkan dengan baris baru atau titik koma (;)')
                    ->afterStateHydrated(function ($component, $state) {
                        if (is_array($state)) {
                            $component->state(implode("\n", $state));
                        } elseif (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (is_array($decoded)) {
                                $component->state(implode("\n", $decoded));
                            }
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        if (blank($state)) {
                            return null; // simpan null, bukan array kosong
                        }

                        return collect(preg_split('/[\r\n;]+/', $state))
                            ->filter(fn($item) => trim($item) !== '')
                            ->map(fn($item) => trim($item))
                            ->values()
                            ->toArray();
                    })
                    ->rows(5),


                Toggle::make('is_active')
                    ->default(true),
            ])->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        try {
            $service = ServicePlan::create($data);
            $this->form->fill();
            Notification::make()
                ->title('Create Plan')
                ->body('A service plan successfully created.')
                ->success()
                ->send();
            $this->dispatch('plan-created');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating Order')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function update(): void
    {
        $data = $this->form->getState();
        try {
            $plan = ServicePlan::findOrFail($this->servicePlanId);
            $plan->update($data);
            $this->form->fill();
            $this->dispatch('plan-updated');
            Notification::make()
                ->title('Plan Update')
                ->body('A service plan successfully updated.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating plan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->serviceId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function delete(): void
    {
        $data = $this->form->getState();
        try {
            $service = ServicePlan::findOrFail($this->serviceId);
            $service->delete();
            $this->servicePlanId = null;
            $this->form->fill();
            $this->dispatch('plan-deleted');
            Notification::make()
                ->title('Delete Plan')
                ->body('A service plan successfully deleted.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error deleting plan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
