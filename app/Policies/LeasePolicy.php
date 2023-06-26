<?php

namespace App\Policies;

use App\Enums\ApprovalStatus;
use App\Models\User;
use App\Models\Lease;
use Illuminate\Auth\Access\Response;

class LeasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list leases');
    }

    public function view(User $user, Lease $lease): bool
    {
        return $user->can('view lease');
    }

    public function create(User $user): bool
    {
        return $user->can('create lease');
    }

    public function update(User $user, Lease $lease): bool
    {
        return $user->can('update lease');
    }

    public function delete(User $user, Lease $lease): bool
    {
        return $user->can('delete lease') && in_array($lease->status, [ApprovalStatus::PENDING_APPROVAL, ApprovalStatus::VOIDED]);
    }

    public function approve(User $user, Lease $lease): bool
    {
        return $user->can('approve lease') && $lease->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function terminate(User $user, Lease $lease): bool
    {
        return $user->can('terminate lease') && $lease->status == ApprovalStatus::APPROVED;
    }
}
