<?php

namespace App\Filament\Fx\Resources\FxUsers\Pages;

use App\Filament\Fx\Resources\FxUsers\FxUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFxUser extends EditRecord
{
    protected static string $resource = FxUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
