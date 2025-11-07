<?php

namespace App\Filament\Fx\Resources\FxUsers\Pages;

use App\Filament\Fx\Resources\FxUsers\FxUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFxUser extends CreateRecord
{
    protected static string $resource = FxUserResource::class;
}
