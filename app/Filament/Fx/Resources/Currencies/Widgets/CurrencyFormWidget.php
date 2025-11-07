<?php

namespace App\Filament\Fx\Resources\Currencies\Widgets;

use App\Models\FX\Currency;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class CurrencyFormWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.fx.resources.currencies.widgets.currency-form-widget';

    public ?int $currencyId = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected $listeners = [
        'currencySelected' => 'onCurrencySelected',
    ];

    public function onCurrencySelected($payload = null)
    {
        $this->currencyId = $payload['id'] ?? null;

        if ($this->currencyId) {
            $currency = Currency::find($this->currencyId);
            if ($currency) {
                $this->data = $currency->toArray();
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
                View::make('info')
                    ->visible(fn ($get) => ! empty($get('symbol')))
                    ->view('filament.fx.resources.currencies.widgets.currency-symbol-placeholder')
                    ->columnSpan(1),
                Group::make()
                    ->columnSpan(3)
                    ->components([
                        TextInput::make('symbol')
                            ->required()
                            ->inlineLabel()
                            ->live()
                            ->rules(['regex:/^[\p{Sc}\pL\pN\s\-_]+$/u']),
                        TextInput::make('name')
                            ->required()
                            ->inlineLabel()
                            ->minLength(2)
                            ->maxLength(255)
                            ->unique()
                            ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                        TextInput::make('alias')
                            ->inlineLabel()
                            ->minLength(2)
                            ->maxLength(6)
                            ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                        TextInput::make('country')
                            ->inlineLabel(),
                        TextInput::make('suffix')
                            ->inlineLabel()
                            ->maxLength(100),
                    ]),
            ])
            ->columns(5)
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        try {
            $currency = Currency::create($data);
            $this->form->fill();
            Notification::make()
                ->title('New Currency')
                ->body('A Currency successfully created.')
                ->success()
                ->send();
            $this->dispatch('currency-created');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating new currency')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->currencyId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function update(): void
    {
        $data = $this->form->getState();
        try {
            $currency = Currency::find($this->currencyId);
            if ($currency) {
                $currency->update($data);
                $this->form->fill();
                Notification::make()
                    ->title('Currency Updated')
                    ->body('Currency data successfully updated.')
                    ->success()
                    ->send();
                $this->dispatch('currency-updated');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Updating currency')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function delete(): void
    {
        $data = $this->form->getState();
        try {
            $currency = Currency::findOrFail($this->currencyId);
            $currency->delete();
            $this->form->fill();
            $this->dispatch('currency-deleted');
            Notification::make()
                ->title('Currency Deleted')
                ->body('A currency successfully deleted.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Deleting currency')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
