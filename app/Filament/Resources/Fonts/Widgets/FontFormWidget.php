<?php

namespace App\Filament\Resources\Fonts\Widgets;

use App\Models\Font;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class FontFormWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.resources.fonts.widgets.font-form-widget';

    public ?int $fontId = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected $listeners = [
        'fontSelected' => 'onFontSelected',
    ];

    public function onFontSelected($payload = null)
    {
        $this->fontId = $payload['id'] ?? null;

        if ($this->fontId) {
            $font = Font::find($this->fontId);
            if ($font) {
                $this->data = $font->toArray();
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
                Textarea::make('source')
                    ->rows(3)
                    ->required(),
            ])->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        try {
            $font = Font::create($data);
            $this->form->fill();
            Notification::make()
                ->title('Create Font')
                ->body('A font successfully created.')
                ->success()
                ->send();
            $this->dispatch('font-created');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating font')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function update(): void
    {
        $data = $this->form->getState();
        try {
            $font = Font::findOrFail($this->fontId);
            $font->update($data);
            $this->form->fill();
            $this->dispatch('font-updated');
            Notification::make()
                ->title('Font Update')
                ->body('A font successfully updated.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating font')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->fontId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function delete(): void
    {
        $data = $this->form->getState();
        try {
            $font = Font::findOrFail($this->fontId);
            $font->delete();
            $this->fontId = null;
            $this->form->fill();
            $this->dispatch('font-deleted');
            Notification::make()
                ->title('Delete Service')
                ->body('A font successfully deleted.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error deleting font')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
