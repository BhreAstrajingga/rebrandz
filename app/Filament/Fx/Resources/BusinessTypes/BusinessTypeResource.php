<?php

namespace App\Filament\Fx\Resources\BusinessTypes;

use App\Filament\Fx\Resources\BusinessTypes\Pages\BusinessTypeManagement;
use App\Filament\Fx\Resources\BusinessTypes\Pages\CreateBusinessType;
use App\Filament\Fx\Resources\BusinessTypes\Pages\EditBusinessType;
use App\Filament\Fx\Resources\BusinessTypes\Pages\ListBusinessTypes;
use App\Filament\Fx\Resources\BusinessTypes\Schemas\BusinessTypeForm;
use App\Filament\Fx\Resources\BusinessTypes\Tables\BusinessTypesTable;
use App\Models\FX\BusinessType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BusinessTypeResource extends Resource
{
    protected static ?string $model = BusinessType::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Business Types';

    protected static ?string $modelLabel = 'Business Type';

    protected static ?string $pluralModelLabel = 'Business Types';

    public static function form(Schema $schema): Schema
    {
        return BusinessTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessTypesTable::configure($table);
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
            'index' => BusinessTypeManagement::route('/'),
            'list' => ListBusinessTypes::route('/list'),
            'create' => CreateBusinessType::route('/create'),
            'edit' => EditBusinessType::route('/{record}/edit'),
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
