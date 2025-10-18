<?php

namespace App\Filament\Resources\ServicePlans\Widgets;

use App\Models\ServicePlan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ServicePlanListWidget extends TableWidget
{
    public $selectedPlanId;

    public function mount($noijin = null)
    {
        $this->selectedPlanId = null;
    }

    protected $listeners = [
        'planSelected' => 'setSelectedPlan',
        'plan-created' => 'refreshPlan',
        'plan-updated' => 'refreshPlan',
        'plan-deleted' => 'refreshPlan',
    ];

    public function setSelectedPlan($payload = null)
    {
        $this->selectedPlanId = $payload['id'] ?? null;
    }

    public function refreshPlan(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedPlanId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ServicePlan::query())
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedPlanId
                    ? 'bg-warning-100 dark:bg-warning-900'
                    : null;
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->action(function (ServicePlan $record) {
                        $this->dispatch('planSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
                TextColumn::make('price')
                    ->searchable()
                    ->money('IDR', decimalPlaces: 2, locale:'id')
                    ->action(function (ServicePlan $record) {
                        $this->dispatch('planSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->action(function (ServicePlan $record) {
                        $this->dispatch('planSelected', ['id' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'cursor-pointer']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('createServicePlan')
                    ->label('Add Plan')
                    ->size('sm')
                    ->icon('heroicon-s-plus')
                    ->size('xs')
                    ->color('primary')
                    ->action(function () {
                        $this->dispatch('planSelected', null);
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->color('danger')
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->defaultGroup('service.name')
            ->groupingSettingsHidden()
            ->groups([
                Group::make('service.name')->titlePrefixedWithLabel(false)->collapsible(),
            ])
            ->emptyStateHeading('No Plan available')
            ->emptyStateDescription('all plans will be listed here.');
    }
}
