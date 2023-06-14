<?php

namespace App\Actions\Invoices;

use App\Models\Tax;
use Carbon\Carbon;
use App\Enums\ApprovalStatus;
use App\Models\Invoices\Invoice;
use App\Jobs\UpdateModelApprovalAction;

class CreateInvoice
{
    public static function handle(array $payload): Invoice|null
    {
        $tax = Tax::where('is_default', true)->first();

        if (!$tax) return null;

        $invoice = Invoice::create([
            'user_id' => auth()->check() ? auth()->id() : '',
            'tax_id' => $tax->id,
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
