<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Lease;
use App\Enums\ApprovalStatus;
use App\Actions\CreateApproval;
use App\Models\Invoices\Invoice;
use App\Models\Expenses\Expense;
use App\Models\Payments\Payment;
use App\Actions\Rent\CreateRent;
use App\Jobs\CreateTenantStatement;
use App\Actions\Discount\CreateDeposit;
use App\Actions\Goodwill\CreateGoodwill;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\CreateBankAccountTransaction;
use App\Http\Requests\ApprovalActionRequest;
use App\Models\Accounts\BankAccountAdjustment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalController extends Controller
{
    public function __invoke(ApprovalActionRequest $request)
    {
        $model = $this->getApprovalModel();

        $this->authorize(request('action'), $model);

        $this->executeApprovalAction($model);

        CreateApproval::handle(model: $model, status: $this->getApprovalStatus(), note: $request->note);

        $this->toast('Successfully ' . $this->getApprovalStatus()->value . ' ' . str(request('modal'))->headline());

        return back();
    }

    protected function getApprovalModel(): Model|Exception
    {
        return match (request('model')) {
            'lease' => Lease::findOrFail(request('id')),
            'Payment' => Payment::findOrFail(request('id')),
            'invoice' => Invoice::findOrFail(request('id')),
            'Expense' => Expense::findOrFail(request('id')),
            'BankAccountAdjustment' => BankAccountAdjustment::with('items.account')->findOrFail(request('id')),
            'default' => throw new ModelNotFoundException()
        };
    }

    protected function getApprovalStatus(): ApprovalStatus
    {
        return match (request('action')) {
            'void' => ApprovalStatus::VOIDED,
            'reverse' => ApprovalStatus::REVERSED,
            'approve' => ApprovalStatus::APPROVED,
        };
    }

    protected function executeApprovalAction(Model $model): void
    {
        $status = $this->getApprovalStatus();

        if ($status == ApprovalStatus::VOIDED) return;

        match (true) {
            $model instanceof Lease => $this->handleLeaseProcesses($model, $status),
            $model instanceof Expense => $this->handleExpenseProcesses($model, $status),
            $model instanceof Invoice => $this->handleInvoiceProcesses($model, $status),
            $model instanceof Payment => $this->handlePaymentProcesses($model, $status),
            $model instanceof BankAccountAdjustment => $this->handleBankAccountAdjustmentProcesses($model, $status)
        };
    }

    protected function handleLeaseProcesses(Lease $lease, ApprovalStatus $status)
    {
        if ($status == ApprovalStatus::APPROVED) {
            CreateRent::handle(lease: $lease);
            CreateDeposit::handle(lease: $lease);
            CreateGoodwill::handle(lease: $lease);
        }
    }

    protected function handleBankAccountAdjustmentProcesses(BankAccountAdjustment $adjustment, ApprovalStatus $status)
    {
        if ($status == ApprovalStatus::APPROVED) {
            $adjustment->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, $adjustment->action));
        }

        if ($status == ApprovalStatus::REVERSED) {
            $adjustment->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, !$adjustment->action));
        }
    }

    protected function handlePaymentProcesses(Payment $payment, ApprovalStatus $status)
    {
        if ($status == ApprovalStatus::APPROVED) {
            CreateBankAccountTransaction::dispatch($payment, $payment->action);
            CreateTenantStatement::dispatch(model: $payment, tenant: $payment->tenant, amount: $payment->amount, description: 'payment', action: !$payment->action);
        }

        if ($status == ApprovalStatus::REVERSED) {
            CreateBankAccountTransaction::dispatch($payment, !$payment->action);
            CreateTenantStatement::dispatch(model: $payment, tenant: $payment->tenant, amount: $payment->amount, description: 'payment reversal', action: $payment->action);
        }
    }

    protected function handleExpenseProcesses(Expense $expense, ApprovalStatus $status)
    {
        if ($status == ApprovalStatus::APPROVED) {
            CreateBankAccountTransaction::dispatch($expense, !$expense->action);
        }

        if ($status == ApprovalStatus::REVERSED) {
            CreateBankAccountTransaction::dispatch($expense, $expense->action);
        }
    }

    protected function handleInvoiceProcesses(Invoice $invoice, ApprovalStatus $status)
    {
        if ($status == ApprovalStatus::APPROVED) {
            CreateTenantStatement::dispatch(model: $invoice, tenant: $invoice->tenant, amount: $invoice->amount, action: $invoice->action);
        }

        if ($status == ApprovalStatus::REVERSED) {
            CreateTenantStatement::dispatch(model: $invoice, tenant: $invoice->tenant, amount: $invoice->amount, action: !$invoice->action);
        }
    }
}
