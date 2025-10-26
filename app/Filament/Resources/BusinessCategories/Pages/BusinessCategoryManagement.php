<?php

namespace App\Filament\Resources\BusinessCategories\Pages;

use App\Filament\Resources\BusinessCategories\BusinessCategoryResource;
use Filament\Resources\Pages\Page;

class BusinessCategoryManagement extends Page
{
    protected static string $resource = BusinessCategoryResource::class;

    protected string $view = 'filament.resources.business-categories.pages.business-category-management';
}
