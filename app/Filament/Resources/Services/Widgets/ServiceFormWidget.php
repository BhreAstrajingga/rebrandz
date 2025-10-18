<?php

namespace App\Filament\Resources\Services\Widgets;

use App\Models\Service;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Form as SchemasForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Log;

class ServiceFormWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.resources.services.widgets.service-form-widget';

    public ?int $serviceId = null;
    public ?array $data = [];


    public function mount(): void
    {
        $this->form->fill();
    }

    protected $listeners = [
        'serviceSelected' => 'onServiceSelected',
    ];

    public function onServiceSelected($payload = null)
    {
        $this->serviceId = $payload['id'] ?? null;

        if ($this->serviceId) {
            $service = Service::find($this->serviceId);
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
                TextInput::make('name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                TextInput::make('type')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                Textarea::make('description')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->default(true),
            ])->statePath('data');
    }

	public function create(): void
	{
		$data = $this->form->getState();
		try {
			$service = Service::create($data);
			$this->form->fill();
            Notification::make()
                ->title('Create Service')
                ->body('A service successfully created.')
                ->success()
                ->send();
            $this->dispatch('service-created');
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
			$service = Service::findOrFail($this->serviceId);
            $service->update($data);
			$this->form->fill();
            $this->dispatch('service-updated');
            Notification::make()
                ->title('Service Update')
                ->body('A service successfully updated.')
                ->success()
                ->send();
        } catch (\Exception $e) {
			Notification::make()
				->title('Error Creating service')
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
			$service = Service::findOrFail($this->serviceId);
            $service->delete();
			$this->serviceId = null;
			$this->form->fill();
            $this->dispatch('service-deleted');
            Notification::make()
                ->title('Delete Service')
                ->body('A service successfully deleted.')
                ->success()
                ->send();
        } catch (\Exception $e) {
			Notification::make()
				->title('Error deleting service')
				->body($e->getMessage())
				->danger()
				->send();
		}
	}
}
