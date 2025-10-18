<?php

namespace App\Models\Concerns;

use App\Support\CurrentTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait TenantScoped
{
    // Tenancy disabled: trait no-ops to avoid scope side-effects.
    public static function bootTenantScoped(): void {}
}
