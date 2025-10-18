<?php

namespace App\Filament\Resources\ServicePlans\Pages;

use App\Filament\Resources\ServicePlans\ServicePlanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServicePlan extends ViewRecord
{
    protected static string $resource = ServicePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
