<?php

namespace App\Filament\Fx\Resources\McAgents;

use App\Filament\Fx\Resources\McAgents\Pages\AgentsManagement;
use App\Filament\Fx\Resources\McAgents\Pages\CreateMcAgent;
use App\Filament\Fx\Resources\McAgents\Pages\EditMcAgent;
use App\Filament\Fx\Resources\McAgents\Pages\ListMcAgents;
use App\Filament\Fx\Resources\McAgents\Pages\ViewMcAgent;
use App\Filament\Fx\Resources\McAgents\Schemas\McAgentForm;
use App\Filament\Fx\Resources\McAgents\Schemas\McAgentInfolist;
use App\Filament\Fx\Resources\McAgents\Tables\McAgentsTable;
use App\Models\McAgent;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class McAgentResource extends Resource
{
    protected static ?string $model = McAgent::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $recordTitleAttribute = 'agent_code';

    protected static ?string $navigationLabel = 'Agents';

    protected static ?string $modelLabel = 'Agent';

    protected static ?string $pluralModelLabel = 'Agents';

    public static function form(Schema $schema): Schema
    {
        return McAgentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return McAgentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return McAgentsTable::configure($table);
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
            'index' => AgentsManagement::route('/'),
            'list' => ListMcAgents::route('/list'),
            'create' => CreateMcAgent::route('/create'),
            'view' => ViewMcAgent::route('/{record}'),
            'edit' => EditMcAgent::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected static function userHasFxAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user !== null && in_array((string) $user->user_type, ['system', 'admin', 'manager', 'staff', 'fx'], true);
    }

    public static function canAccess(): bool
    {
        return static::userHasFxAccess();
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return static::userHasFxAccess();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::userHasFxAccess();
    }
}
