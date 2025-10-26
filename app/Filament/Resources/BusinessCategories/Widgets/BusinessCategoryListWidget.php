<?php

namespace App\Filament\Resources\BusinessCategories\Widgets;

use App\Models\BusinessCategory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BusinessCategoryListWidget extends TableWidget
{
    public $selectedCategoryId;

    public function mount($selectedCategoryId = null)
    {
        $this->selectedCategoryId = $selectedCategoryId;
    }

    protected $listeners = [
        'category-selected' => 'setCategorySelected',
        'category-created' => 'refreshCategory',
        'category-updated' => 'refreshCategory',
        'category-deleted' => 'refreshCategory',
    ];

    public function setCategorySelected($payload = null)
    {
        if (is_array($payload) && array_key_exists('id', $payload)) {
            $this->selectedCategoryId = (int) $payload['id'];
        } else {
            $this->selectedCategoryId = null;
        }
        $this->dispatch('$refresh');
    }

    public function refreshCategory(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedCategoryId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => BusinessCategory::query()->orderBy('name', 'asc'))
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedCategoryId
                    ? 'bg-[var(--color-warning-100)] dark:bg-[var(--color-warning-900)]'
                    : null;
            })
            ->columns([
                TextColumn::make('name')->label('Category')
                    ->action(function (BusinessCategory $record) {
                        // Update selection directly to ensure immediate re-render
                        $this->selectedCategoryId = (int) $record->getKey();
                        $this->dispatch('$refresh');
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('createCategory')
                    ->label('Add Category')
                    ->size('sm')
                    ->icon('heroicon-s-plus')
                    ->size('xs')
                    ->color('primary')
                    ->action(function () {
                        $this->dispatch('category-selected', null);
                    }),
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->emptyStateHeading('No Category available')
            ->emptyStateDescription('all categories will be listed here.');
    }
}
