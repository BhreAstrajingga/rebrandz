<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Font;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FontPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Font');
    }

    public function view(AuthUser $authUser, Font $font): bool
    {
        return $authUser->can('View:Font');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Font');
    }

    public function update(AuthUser $authUser, Font $font): bool
    {
        return $authUser->can('Update:Font');
    }

    public function delete(AuthUser $authUser, Font $font): bool
    {
        return $authUser->can('Delete:Font');
    }

    public function restore(AuthUser $authUser, Font $font): bool
    {
        return $authUser->can('Restore:Font');
    }

    public function forceDelete(AuthUser $authUser, Font $font): bool
    {
        return $authUser->can('ForceDelete:Font');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Font');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Font');
    }

    public function replicate(AuthUser $authUser, Font $font): bool
    {
        return $authUser->can('Replicate:Font');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Font');
    }
}
