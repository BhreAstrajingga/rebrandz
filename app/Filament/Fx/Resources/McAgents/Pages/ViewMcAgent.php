<?php

namespace App\Filament\Fx\Resources\McAgents\Pages;

use App\Filament\Fx\Resources\McAgents\McAgentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMcAgent extends ViewRecord
{
    protected static string $resource = McAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
