<?php

namespace App\Filament\Resources\ServicePlans;

use App\Filament\Resources\ServicePlans\Pages\CreateServicePlan;
use App\Filament\Resources\ServicePlans\Pages\EditServicePlan;
use App\Filament\Resources\ServicePlans\Pages\ListServicePlans;
use App\Filament\Resources\ServicePlans\Pages\ServicePlanManagement;
use App\Filament\Resources\ServicePlans\Pages\ViewServicePlan;
use App\Filament\Resources\ServicePlans\Schemas\ServicePlanForm;
use App\Filament\Resources\ServicePlans\Schemas\ServicePlanInfolist;
use App\Filament\Resources\ServicePlans\Tables\ServicePlansTable;
use App\Models\ServicePlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicePlanResource extends Resource
{
    protected static ?string $model = ServicePlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ServicePlanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServicePlanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicePlansTable::configure($table);
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
            'index' => ServicePlanManagement::route('/'),
            'list' => ListServicePlans::route('/list'),
            'create' => CreateServicePlan::route('/create'),
            'view' => ViewServicePlan::route('/{record}'),
            'edit' => EditServicePlan::route('/{record}/edit'),
        ];
    }
}
