<?php

namespace App\Filament\Fx\Resources\Currencies;

use App\Filament\Fx\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Fx\Resources\Currencies\Pages\CurrencyManagement;
use App\Filament\Fx\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Fx\Resources\Currencies\Pages\ListCurrencies;
use App\Filament\Fx\Resources\Currencies\Schemas\CurrencyForm;
use App\Filament\Fx\Resources\Currencies\Tables\CurrenciesTable;
use App\Models\FX\Currency;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CurrencyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurrenciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => CurrencyManagement::route('/'),
            'list' => ListCurrencies::route('/list'),
            'create' => CreateCurrency::route('/create'),
            'edit' => EditCurrency::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
