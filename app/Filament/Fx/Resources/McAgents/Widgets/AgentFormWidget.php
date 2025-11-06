<?php

namespace App\Filament\Fx\Resources\McAgents\Widgets;

use App\Models\McAgent;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class AgentFormWidget extends Widget implements HasForms
{
	use InteractsWithForms;
    protected string $view = 'filament.fx.widgets.agent-form-widget';
	public ?int $agentId = null;
    public ?array $data = [];


    public function mount(): void
    {
        $this->form->fill();
    }

    protected $listeners = [
        'agentSelected' => 'onAgentSelected',
    ];

    public function onAgentSelected($payload = null)
    {
        $this->agentId = $payload['id'] ?? null;

        if ($this->agentId) {
            $agent = McAgent::find($this->agentId);
            if ($agent) {
                $this->data = $agent->toArray();
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
					->columnSpanFull()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                TextInput::make('email')
					->email()
                    ->maxLength(1000)
					->columnSpan(1),
				TextInput::make('phone')
					->maxLength(25)
					->columnSpan(1),
            ])
			->columns(2)
			->statePath('data');
    }

	public function create(): void
	{
		$data = $this->form->getState();
		try {
			$agent = McAgent::create($data);
			$this->form->fill();
            Notification::make()
                ->title('New Agent')
                ->body('An agent successfully created.')
                ->success()
                ->send();
            $this->dispatch('agent-created');
        } catch (\Exception $e) {
			Notification::make()
				->title('Error Creating new agent')
				->body($e->getMessage())
				->danger()
				->send();
		}
	}

	public function save(): void
	{
		if ($this->agentId) {
			$this->update();
		} else {
			$this->create();
		}
	}
}
