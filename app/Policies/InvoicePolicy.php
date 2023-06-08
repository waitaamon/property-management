<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\ApprovalStatus;
use App\Models\Invoices\Invoice;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list invoices');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->can('view invoice');
    }

    public function create(User $user): bool
    {
        return $user->can('create invoice');
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return  $user->can('edit invoice') && $invoice->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return  $user->can('delete invoice') && $invoice->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function approve(User $user, Invoice $invoice): bool
    {
        return  $user->can('approve invoice') && $invoice->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function reverse(User $user, Invoice $invoice): bool
    {
        return  $user->can('reverse invoice') && $invoice->status == ApprovalStatus::APPROVED;
    }

    public function print(User $user, Invoice $invoice): bool
    {
        return  $user->can('print invoice') && $invoice->status == ApprovalStatus::APPROVED;
    }
}
