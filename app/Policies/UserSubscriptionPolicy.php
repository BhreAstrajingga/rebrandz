<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\UserSubscription;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserSubscriptionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UserSubscription');
    }

    public function view(AuthUser $authUser, UserSubscription $userSubscription): bool
    {
        return $authUser->can('View:UserSubscription');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UserSubscription');
    }

    public function update(AuthUser $authUser, UserSubscription $userSubscription): bool
    {
        return $authUser->can('Update:UserSubscription');
    }

    public function delete(AuthUser $authUser, UserSubscription $userSubscription): bool
    {
        return $authUser->can('Delete:UserSubscription');
    }

    public function restore(AuthUser $authUser, UserSubscription $userSubscription): bool
    {
        return $authUser->can('Restore:UserSubscription');
    }

    public function forceDelete(AuthUser $authUser, UserSubscription $userSubscription): bool
    {
        return $authUser->can('ForceDelete:UserSubscription');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UserSubscription');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UserSubscription');
    }

    public function replicate(AuthUser $authUser, UserSubscription $userSubscription): bool
    {
        return $authUser->can('Replicate:UserSubscription');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UserSubscription');
    }
}
