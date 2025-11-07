<?php

namespace App\Filament\Resources\Fonts\Pages;

use App\Filament\Resources\Fonts\FontResource;
use Filament\Resources\Pages\Page;

class FontManagement extends Page
{
    public ?array $data = [];

    public $selectedFontId;

    protected static string $resource = FontResource::class;

    protected string $view = 'filament.resources.fonts.pages.font-management';
}
