<?php

namespace App\Filament\Fx\Resources\BusinessTypes\Pages;

use App\Filament\Fx\Resources\BusinessTypes\BusinessTypeResource;
use Filament\Resources\Pages\Page;

class BusinessTypeManagement extends Page
{
    protected static string $resource = BusinessTypeResource::class;

    protected string $view = 'filament.fx.resources.business-types.pages.business-type-management';
}
