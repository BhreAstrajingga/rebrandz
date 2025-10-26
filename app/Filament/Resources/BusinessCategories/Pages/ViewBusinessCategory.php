<?php

namespace App\Filament\Resources\BusinessCategories\Pages;

use App\Filament\Resources\BusinessCategories\BusinessCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBusinessCategory extends ViewRecord
{
    protected static string $resource = BusinessCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
