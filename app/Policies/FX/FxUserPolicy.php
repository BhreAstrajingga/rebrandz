<?php

namespace App\Policies\FX;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FxUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FxUser');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:FxUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FxUser');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:FxUser');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:FxUser');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('Restore:FxUser');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDelete:FxUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FxUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FxUser');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('Replicate:FxUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FxUser');
    }
}
