<?php

namespace App\Filament\Fx\Resources\FxUsers\Pages;

use App\Filament\Fx\Resources\FxUsers\FxUserResource;
use Filament\Resources\Pages\Page;

class FxUserManagement extends Page
{
    protected static string $resource = FxUserResource::class;

    protected string $view = 'filament.fx.resources.fx-users.pages.fx-user-management';

    protected static ?string $title = 'User Management';

    protected ?string $heading = 'Manage Users';
}
