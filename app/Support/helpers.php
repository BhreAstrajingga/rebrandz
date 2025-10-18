<?php

use App\Support\CurrentTenant;
use App\Models\Tenant;

// Tenancy disabled; helper retained to avoid breaking existing views/tests.
if (! function_exists('tenant')) {
    function tenant(): ?Tenant
    {
        return null;
    }
}
