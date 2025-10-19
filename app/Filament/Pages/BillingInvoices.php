<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BillingInvoices extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Invoices';

    protected static \UnitEnum|string|null $navigationGroup = 'Billing';

    protected static ?string $slug = 'billing/invoices';

    protected string $view = 'filament.pages.billing-invoices';

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'user';
    }
}
