<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvoiceListWidget extends TableWidget
{

    public function table(Table $table): Table
    {
        return $table
            ->heading(null)
            ->emptyStateHeading('No invoices found.')
            ->emptyStateDescription('You have not generated any invoices yet.')
            ->records(fn (): array => [
                1 => [
                    'invoice_number' => '16258-1224218',
                    'invoice_date' => '2025-10-05 00:00:00',
                    'due_date' => '2025-10-11 00:00:00',
                    'status' => 'PAID',
                    'total' => '15',
                    'items' => [
                        'PyMap Basic Plan',
                        'Limited GeoPandas Library Access',
                        'Limited Fionas Library Access',
                        'Limited API request',
                    ],
                ],
                2 => [
                    'invoice_number' => '16258-1224217',
                    'invoice_date' => '2025-9-06 00:00:00',
                    'due_date' => '2025-10-05 00:00:00',
                    'status' => 'PAID',
                    'total' => '47',
                    'items' => [
                        'PyMap Standard Plan',
                        'GeoPandas Library Access',
                        'Fionas Library Access',
                        'Unlimited API request',
                    ],
                ],
                3 => [
                    'invoice_number' => '16258-1224216',
                    'invoice_date' => '2025-8-05 00:00:00',
                    'due_date' => '2025-9-05 00:00:00',
                    'status' => 'PAID',
                    'total' => '47',
                    'items' => [
                        'PyMap Standard Plan',
                        'GeoPandas Library Access',
                        'Fionas Library Access',
                        'Unlimited API request',
                    ],
                ],
            ])
            ->columns([
                TextColumn::make('invoice_number'),
                TextColumn::make('invoice_date')->date(),
                TextColumn::make('due_date')->date(),
                TextColumn::make('total')->money('USD', divideBy:1),
                TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->colors([
                        'success' => 'PAID',
                        'danger' => 'UNPAID',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('Download')
                    ->iconButton()
                    ->tooltip('Download Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
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
