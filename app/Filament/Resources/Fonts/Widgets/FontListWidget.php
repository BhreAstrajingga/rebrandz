<?php

namespace App\Filament\Resources\Fonts\Widgets;

use App\Models\Font;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FontListWidget extends TableWidget
{
    public $selectedFontId;

    public function mount($noijin = null)
    {
        $this->selectedFontId = null;
    }

    protected $listeners = [
        'fontSelected' => 'setSelectedFont',
        'font-created' => 'refreshFonts',
        'font-updated' => 'refreshFonts',
        'font-deleted' => 'refreshFonts',
    ];

    public function setSelectedFont($payload = null)
    {
        $this->selectedFontId = $payload['id'] ?? null;
    }

    public function refreshFonts(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedFontId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Font::query())
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedFontId
                    ? 'bg-warning-100 dark:bg-warning-900 cursor-pointer'
                    : null;
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Font Name')
                    ->searchable()
                    ->action(function (Font $record) {
                        $this->dispatch('fontSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('createFont')
                    ->label('Add Font')
                    ->size('sm')
                    ->icon('heroicon-s-plus')
                    ->size('xs')
                    ->color('primary')
                    ->action(function () {
                        $this->dispatch('fontSelected', null);
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
