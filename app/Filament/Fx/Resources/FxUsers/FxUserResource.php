<?php

namespace App\Filament\Fx\Resources\FxUsers;

use App\Filament\Fx\Resources\FxUsers\Pages\CreateFxUser;
use App\Filament\Fx\Resources\FxUsers\Pages\EditFxUser;
use App\Filament\Fx\Resources\FxUsers\Pages\FxUserManagement;
use App\Filament\Fx\Resources\FxUsers\Pages\ListFxUsers;
use App\Filament\Fx\Resources\FxUsers\Schemas\FxUserForm;
use App\Filament\Fx\Resources\FxUsers\Tables\FxUsersTable;
use App\Models\FX\FxUser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FxUserResource extends Resource
{
    protected static ?string $model = FxUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    public static function form(Schema $schema): Schema
    {
        return FxUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FxUsersTable::configure($table);
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
            'index' => FxUserManagement::route('/'),
            // 'list' => ListFxUsers::route('/list'),
            // 'create' => CreateFxUser::route('/create'),
            // 'edit' => EditFxUser::route('/{record}/edit'),
        ];
    }
}
