<?php

namespace App\Filament\Resources\BusinessCategories\Widgets;

use App\Models\BusinessCategory;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class BusinessCategoryFormWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected string $view = 'filament.resources.business-categories.widgets.business-category-form-widget';

    public ?int $categoryId = null;

    public ?array $data = [];

    protected $listeners = [
        'category-selected' => 'setCategorySelected',
        'category-created' => 'refreshCategory',
        'category-updated' => 'refreshCategory',
        'category-deleted' => 'refreshCategory',
    ];

    public function mount(): void
    {
        $this->form->fill([]);
    }

    public function setCategorySelected($payload = null): void
    {
        $this->categoryId = $payload['id'] ?? null;

        if ($this->categoryId) {
            $category = BusinessCategory::find($this->categoryId);
            if ($category) {
                $this->form->fill(array_merge(
                    $category->toArray(),
                ));
            }
        } else {
            $this->form->fill([]);
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
                    ->unique()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                Textarea::make('description')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->default(true),
            ])->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        try {
            $category = BusinessCategory::create($data);
            $this->form->fill();
            Notification::make()
                ->title('Create category')
                ->body('A category successfully created.')
                ->success()
                ->send();
            $this->dispatch('category-created');
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
            $service = BusinessCategory::findOrFail($this->categoryId);
            $service->update($data);
            $this->form->fill();
            $this->dispatch('category-updated');
            Notification::make()
                ->title('category Update')
                ->body('A category successfully updated.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Creating category')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        if ($this->categoryId) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function delete(): void
    {
        $data = $this->form->getState();
        try {
            $category = BusinessCategory::findOrFail($this->categoryId);
            $category->delete();
            $this->categoryId = null;
            $this->form->fill();
            $this->dispatch('category-deleted');
            Notification::make()
                ->title('Delete category')
                ->body('A category successfully deleted.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error deleting category')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
