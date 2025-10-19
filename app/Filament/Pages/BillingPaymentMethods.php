<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BillingPaymentMethods extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Payment Methods';

    protected static \UnitEnum|string|null $navigationGroup = 'Billing';

    protected static ?string $slug = 'billing/payment-methods';

    protected string $view = 'filament.pages.billing-payment-methods';

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'user';
    }
}
