<?php

namespace App\Filament\Resources\UserSubscriptions\Pages;

use App\Filament\Resources\UserSubscriptions\UserSubscriptionResource;
use Filament\Resources\Pages\Page;

class UserSubscriptionManagement extends Page
{
    protected static string $resource = UserSubscriptionResource::class;

    protected string $view = 'filament.resources.user-subscriptions.pages.user-subscription-management';
}
