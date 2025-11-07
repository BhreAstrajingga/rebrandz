<?php

namespace App\Filament\Fx\Resources\FxUsers\Pages;

use App\Filament\Fx\Resources\FxUsers\FxUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFxUsers extends ListRecords
{
    protected static string $resource = FxUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
