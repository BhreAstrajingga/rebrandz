<?php

namespace App\Filament\Fx\Resources\BusinessTypes\Widgets;

use App\Models\FX\BusinessType;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BusinessTypeListWidget extends TableWidget
{
    public $selectedBusinessTypeId;

    public function mount($selectedBusinessTypeId = null)
    {
        $this->selectedBusinessTypeId = null;
    }

    protected $listeners = [
        'businessTypeSelected' => 'setBusinessTypeSelected',
        'business-type-created' => 'refreshBusinessType',
        'business-type-updated' => 'refreshBusinessType',
        'business-type-deleted' => 'refreshBusinessType',
    ];

    public function setBusinessTypeSelected($payload = null)
    {
        $this->selectedBusinessTypeId = $payload['id'] ?? null;
    }

    public function refreshBusinessType(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedBusinessTypeId = null;
        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => BusinessType::query())
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedBusinessTypeId
                    ? 'bg-warning-100 dark:bg-warning-900 cursor-pointer'
                    : 'cursor-pointer';
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->action(function (Model $record) {
                        $this->dispatch('businessTypeSelected', ['id' => $record->id]);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New Business Type')
                    ->icon('heroicon-o-plus')
                    ->size('sm')->action(function () {
                        $this->dispatch('businessTypeSelected', null);
                    }),
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
