<?php

namespace App\Filament\Widgets;

use App\Models\UserSubscription;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionListWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading(null)
            ->emptyStateHeading('You have no subscriptions.')
            ->emptyStateDescription('Subscribe to a service to get started.')
            ->query(fn (): Builder => UserSubscription::query()
                ->where('customer_id', Auth::user()->id)
            )
            ->columns([
                TextColumn::make('subscription_code'),
                TextColumn::make('service.name')->label('Service'),
                TextColumn::make('plan.name')->label('Plan'),
                TextColumn::make('end_date')
                    ->label('Due Date')
                    ->date('d M Y'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'ACTIVE',
                        'info' => 'TRIAL',
                        'warning' => 'PENDING',
                        'danger' => 'CANCELLED',
                        'gray' => 'EXPIRED',
                    ]),
                TextColumn::make('remaining_duration')->label('Sisa Durasi'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('Renewal')
                    ->iconButton()
                    ->color('warning')
                    ->tooltip('Renew Subscription')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (array $record) {
                        return redirect()->route('invoices.download', [
                            'invoice' => base64_encode(json_encode($record)),
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
