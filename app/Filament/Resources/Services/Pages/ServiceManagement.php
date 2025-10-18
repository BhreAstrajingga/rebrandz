<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;

class ServiceManagement extends Page
{
	public ?array $data = [];
	public $selectedServiceId;

    protected static string $resource = ServiceResource::class;

    protected string $view = 'filament.resources.services.pages.service-management';

}
