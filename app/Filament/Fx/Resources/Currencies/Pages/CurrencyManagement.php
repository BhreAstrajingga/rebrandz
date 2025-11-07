<?php

namespace App\Filament\Fx\Resources\Currencies\Pages;

use App\Filament\Fx\Resources\Currencies\CurrencyResource;
use Filament\Resources\Pages\Page;

class CurrencyManagement extends Page
{
    protected static string $resource = CurrencyResource::class;

    protected string $view = 'filament.fx.resources.currencies.pages.currency-management';
}
