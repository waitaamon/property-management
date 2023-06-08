<?php

namespace App\Actions\Invoices;

use Carbon\Carbon;
use App\Enums\ApprovalStatus;
use App\Models\Invoices\Invoice;
use App\Jobs\UpdateModelApprovalAction;

class CreateInvoice
{
    public static function handle(array $payload): Invoice|null
    {
        $invoice = Invoice::create([
            'user_id' => auth()->check() ? auth()->id() : '',
            'note' => $payload['note'],
            'amount' => $payload['amount'],
            'lease_id' => $payload['lease'],
            'from' => Carbon::parse($payload['from']),
            'to' => Carbon::parse($payload['to']),
        ]);

        self::approveInvoice($invoice);

        return $invoice;
    }

    protected static function approveInvoice(Invoice $invoice): void
    {
        if (!auth()->check() || auth()->user()->cannot('approve', $invoice)) {
            return;
        }

        UpdateModelApprovalAction::dispatch($invoice, $invoice->user, ApprovalStatus::APPROVED, $invoice->note);

    }
}
