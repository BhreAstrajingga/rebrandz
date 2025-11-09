<?php

declare(strict_types=1);

namespace App\Policies\FX;

use App\Models\FX\BusinessType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class BusinessTypePolicy
{
    use HandlesAuthorization;

    protected function userHasFxAccess(AuthUser $user): bool
    {
        return in_array((string) ($user->user_type ?? ''), ['system', 'admin', 'manager', 'staff', 'fx'], true);
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function view(AuthUser $authUser, BusinessType $businessType): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function update(AuthUser $authUser, BusinessType $businessType): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function delete(AuthUser $authUser, BusinessType $businessType): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function restore(AuthUser $authUser, BusinessType $businessType): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function forceDelete(AuthUser $authUser, BusinessType $businessType): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function replicate(AuthUser $authUser, BusinessType $businessType): bool
    {
        return $this->userHasFxAccess($authUser);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->userHasFxAccess($authUser);
    }
}
