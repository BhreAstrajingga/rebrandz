<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\McAgent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class McAgentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:McAgent');
    }

    public function view(AuthUser $authUser, McAgent $mcAgent): bool
    {
        return $authUser->can('View:McAgent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:McAgent');
    }

    public function update(AuthUser $authUser, McAgent $mcAgent): bool
    {
        return $authUser->can('Update:McAgent');
    }

    public function delete(AuthUser $authUser, McAgent $mcAgent): bool
    {
        return $authUser->can('Delete:McAgent');
    }

    public function restore(AuthUser $authUser, McAgent $mcAgent): bool
    {
        return $authUser->can('Restore:McAgent');
    }

    public function forceDelete(AuthUser $authUser, McAgent $mcAgent): bool
    {
        return $authUser->can('ForceDelete:McAgent');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:McAgent');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:McAgent');
    }

    public function replicate(AuthUser $authUser, McAgent $mcAgent): bool
    {
        return $authUser->can('Replicate:McAgent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:McAgent');
    }
}
