<?php

namespace App\Filament\Fx\Resources\FxUsers\Widgets;

use App\Models\FX\FxUser;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserListWidget extends TableWidget
{
    public $selectedUserId;

    public function mount($noijin = null)
    {
        $this->selectedUserId = null;
    }

    protected $listeners = [
        'userSelected' => 'setSelectedUser',
        'user-created' => 'refreshUsers',
        'user-updated' => 'refreshUsers',
        'user-deleted' => 'refreshUsers',
    ];

    public function setSelectedUser($payload = null)
    {
        $this->selectedUserId = $payload['id'] ?? null;
    }

    public function refreshUsers(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedUserId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => FxUser::query())
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedUserId
                    ? 'bg-warning-100 dark:bg-warning-900 cursor-pointer'
                    : null;
            })
            ->columns([
                TextColumn::make('name')
                    ->label('User Name')
                    ->searchable()
                    ->action(function (FxUser $record) {
                        $this->dispatch('userSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('createUser')
                    ->label('Add User')
                    ->size('sm')
                    ->icon('heroicon-s-plus')
                    ->size('xs')
                    ->color('primary')
                    ->action(function () {
                        $this->dispatch('userSelected', null);
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
