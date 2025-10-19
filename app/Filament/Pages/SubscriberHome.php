<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class SubscriberHome extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $slug = 'dashboard';

    protected string $view = 'filament.pages.subscriber-home';
}

