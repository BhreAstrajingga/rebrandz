<?php

namespace App\Filament\Fx\Resources\McAgents\Pages;

use App\Filament\Fx\Resources\McAgents\McAgentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMcAgents extends ListRecords
{
    protected static string $resource = McAgentResource::class;

	protected ?string $heading = 'Agents';
	protected static ?string $title = 'Agents';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
