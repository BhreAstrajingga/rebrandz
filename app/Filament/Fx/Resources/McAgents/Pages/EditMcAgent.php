<?php

namespace App\Filament\Fx\Resources\McAgents\Pages;

use App\Filament\Fx\Resources\McAgents\McAgentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMcAgent extends EditRecord
{
    protected static string $resource = McAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
