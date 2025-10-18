<?php

namespace App\Filament\Resources\ServicePlans\Pages;

use App\Filament\Resources\ServicePlans\ServicePlanResource;
use Filament\Resources\Pages\Page;

class ServicePlanManagement extends Page
{
    protected static string $resource = ServicePlanResource::class;
    protected ?string $heading = 'Service Plans';

    protected string $view = 'filament.resources.service-plans.pages.service-plan-management';


}
