<?php

namespace App\Filament\Fx\Resources\Currencies\Widgets;

use App\Models\FX\Currency;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CurrencyListWidget extends TableWidget
{
    public $selectedCurrencyId;

    public function mount($selectedCurrencyId = null)
    {
        $this->selectedCurrencyId = null;
    }

    protected $listeners = [
        'currencySelected' => 'setCurrencySelected',
        'currency-created' => 'refreshCurrency',
        'currency-updated' => 'refreshCurrency',
        'currency-deleted' => 'refreshCurrency',
    ];

    public function setCurrencySelected($payload = null)
    {
        $this->selectedCurrencyId = $payload['id'] ?? null;
    }

    public function refreshCurrency(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedCurrencyId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Currency::query()
                    ->orderBy('name', 'asc')
                    ->limit(10))
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedCurrencyId
                    ? 'bg-warning-100 dark:bg-warning-900 cursor-pointer'
                    : 'cursor-pointer';
            })
            ->columns([
                TextColumn::make('symbol')
                    ->label('Symbol')
                    ->action(function (Currency $record) {
                        $this->dispatch('currencySelected', ['id' => $record->id]);
                    }),
                TextColumn::make('alias')
                    ->label('Code')
                    ->action(function (Currency $record) {
                        $this->dispatch('currencySelected', ['id' => $record->id]);
                    }),
                TextColumn::make('name')
                    ->label('Name')
                    ->action(function (Currency $record) {
                        $this->dispatch('currencySelected', ['id' => $record->id]);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('New Currency')
                    ->icon('heroicon-o-plus')
                    ->size('sm')->action(function () {
                        $this->dispatch('currencySelected', null);
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
