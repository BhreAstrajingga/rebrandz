<?php

namespace App\Filament\Resources\Services\Widgets;

use App\Models\Service;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ServiceListWidget extends TableWidget
{
    public $selectedServiceId;

    public function mount($noijin = null)
    {
        $this->selectedServiceId = null;
    }

    protected $listeners = [
        'serviceSelected' => 'setSelectedService',
        'service-created' => 'refreshServices',
        'service-updated' => 'refreshServices',
        'service-deleted' => 'refreshServices',
    ];

    public function setSelectedService($payload = null)
    {
        $this->selectedServiceId = $payload['id'] ?? null;
    }

    public function refreshServices(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedServiceId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Service::query())
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedServiceId
                    ? 'bg-warning-100 dark:bg-warning-900 cursor-pointer'
                    : 'cursor-pointer';
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->action(function (Service $record) {
                        $this->dispatch('serviceSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
                IconColumn::make('is_active')
                    ->boolean()
                    ->action(function (Service $record) {
                        $this->dispatch('serviceSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
            ])
            ->headerActions([
                Action::make('createService')
                    ->label('Add Service')
                    ->size('sm')
                    ->icon('heroicon-s-plus')
                    ->size('xs')
                    ->color('primary')
                    ->action(function () {
                        $this->dispatch('serviceSelected', null);
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->color('danger')
                    ->iconButton(),
            ])
            ->defaultGroup('type')
            ->groupingSettingsHidden()
            ->groups([
                Group::make('type')->titlePrefixedWithLabel(false)->collapsible(),
            ])
            ->emptyStateHeading('No Service available')
            ->emptyStateDescription('all services will be listed here.');
    }
}
