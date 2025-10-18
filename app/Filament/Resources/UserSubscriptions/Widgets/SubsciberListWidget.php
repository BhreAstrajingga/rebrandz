<?php

namespace App\Filament\Resources\UserSubscriptions\Widgets;

use App\Models\UserSubscription;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubsciberListWidget extends TableWidget
{
    public $selectedSubscriptionId;

    public function mount($noijin = null)
    {
        $this->selectedSubscriptionId = null;
    }

    protected $listeners = [
        'subscriptionSelected' => 'setSubscriptionSelected',
        'subscription-created' => 'refreshSubscription',
        'subscription-updated' => 'refreshSubscription',
        'subscription-deleted' => 'refreshSubscription',
    ];

    public function setSubscriptionSelected($payload = null)
    {
        $this->selectedSubscriptionId = $payload['id'] ?? null;
    }

    public function refreshSubscription(): void
    {
        // kosongkan selected agar tidak highlight record lama
        $this->selectedSubscriptionId = null;

        // reset query dan reload tabel
        $this->resetTable();
        $this->dispatch('$refresh');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => UserSubscription::query())
            ->recordClasses(function (Model $record) {
                return (int) $record->getKey() === (int) $this->selectedSubscriptionId
                    ? 'bg-warning-100 dark:bg-warning-900'
                    : null;
            })
            ->columns([
                TextColumn::make('service.name')->label('Plan')
                    ->action(function (UserSubscription $record) {
                        $this->dispatch('subscriptionSelected', ['id' => $record->id]);
                    }),
                TextColumn::make('plan.name')->label('Plan')
                    ->action(function (UserSubscription $record) {
                        $this->dispatch('subscriptionSelected', ['id' => $record->id]);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('createSubscription')
                    ->label('Add Subscription')
                    ->size('sm')
                    ->icon('heroicon-s-plus')
                    ->size('xs')
                    ->color('primary')
                    ->action(function () {
                        $this->dispatch('subscriptionSelected', null);
                    }),
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])->defaultGroup('customer.name')
            ->groupingSettingsHidden()
            ->groups([
                Group::make('customer.name')->titlePrefixedWithLabel(false)->collapsible(),
            ])
            ->emptyStateHeading('No subscription available')
            ->emptyStateDescription('all subscription will be listed here.');
    }
}
