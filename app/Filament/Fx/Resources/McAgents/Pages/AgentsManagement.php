<?php

namespace App\Filament\Fx\Resources\McAgents\Pages;

use App\Filament\Fx\Resources\McAgents\McAgentResource;
use Filament\Resources\Pages\Page;

class AgentsManagement extends Page
{
    protected static string $resource = McAgentResource::class;
	protected ?string $heading = 'Manage Agents';
	protected static ?string $title = 'Manage Agents';

    protected string $view = 'filament.fx.resources.mc-agents.pages.agents-management';
}
