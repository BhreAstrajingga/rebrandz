<?php

namespace App\Support;

use App\Models\Tenant;

class CurrentTenant
{
    public function __construct(private ?Tenant $tenant = null) {}

    public function set(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->tenant;
    }
}

