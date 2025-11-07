<?php

namespace App\Filament\Resources\Fonts;

use App\Filament\Resources\Fonts\Pages\CreateFont;
use App\Filament\Resources\Fonts\Pages\EditFont;
use App\Filament\Resources\Fonts\Pages\FontManagement;
use App\Filament\Resources\Fonts\Pages\ListFonts;
use App\Filament\Resources\Fonts\Schemas\FontForm;
use App\Filament\Resources\Fonts\Tables\FontsTable;
use App\Models\Font;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FontResource extends Resource
{
    protected static ?string $model = Font::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedItalic;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FontForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FontsTable::configure($table);
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
            'index' => FontManagement::route('/'),
            'list' => ListFonts::route('/list'),
            'create' => CreateFont::route('/create'),
            'edit' => EditFont::route('/{record}/edit'),
        ];
    }
}
