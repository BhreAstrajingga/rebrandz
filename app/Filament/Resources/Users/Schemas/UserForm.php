<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->unique()
                    ->rules(['regex:/^[\pL\pN\s\-_]+$/u']),
                TextInput::make('password')
                    ->password(),
				Select::make('roles')
                    ->label('Roles')
                    ->preload()
                    ->multiple()
                    ->relationship('roles', 'name', modifyQueryUsing: fn ($query) => $query->where('guard_name', 'web')),
            ]);
    }
}
