<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ServicePlan;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ServicePlanPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ServicePlan');
    }

    public function view(AuthUser $authUser, ServicePlan $servicePlan): bool
    {
        return $authUser->can('View:ServicePlan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ServicePlan');
    }

    public function update(AuthUser $authUser, ServicePlan $servicePlan): bool
    {
        return $authUser->can('Update:ServicePlan');
    }

    public function delete(AuthUser $authUser, ServicePlan $servicePlan): bool
    {
        return $authUser->can('Delete:ServicePlan');
    }

    public function restore(AuthUser $authUser, ServicePlan $servicePlan): bool
    {
        return $authUser->can('Restore:ServicePlan');
    }

    public function forceDelete(AuthUser $authUser, ServicePlan $servicePlan): bool
    {
        return $authUser->can('ForceDelete:ServicePlan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ServicePlan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ServicePlan');
    }

    public function replicate(AuthUser $authUser, ServicePlan $servicePlan): bool
    {
        return $authUser->can('Replicate:ServicePlan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ServicePlan');
    }
}
