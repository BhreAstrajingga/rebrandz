<?php

namespace App\Filament\Resources\ServicePlans\Pages;

use App\Filament\Resources\ServicePlans\ServicePlanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServicePlan extends EditRecord
{
    protected static string $resource = ServicePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
