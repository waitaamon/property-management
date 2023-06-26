<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Lease;
use App\Enums\ApprovalStatus;
use App\Models\Invoices\Invoice;
use App\Models\Expenses\Expense;
use App\Models\Payments\Payment;
use App\Jobs\CreateAccountStatement;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\UpdateModelApprovalAction;
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

        UpdateModelApprovalAction::dispatch($model, auth()->user(), $this->getApprovalStatus(), request('note'));

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
        if ($this->getApprovalStatus() == ApprovalStatus::VOIDED) {
            return;
        }

        if ($this->getApprovalStatus() == ApprovalStatus::APPROVED) {

            if ($model instanceof BankAccountAdjustment) {
                $model->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, $model->action));
            }

            if ($model instanceof Invoice) {
                CreateAccountStatement::dispatch($model, $model->amount, $model->action);
            }


            if ($model instanceof Payment) {
                CreateBankAccountTransaction::dispatch($model, $model->action);
                CreateAccountStatement::dispatch($model, $model->amount, !$model->action);
            }

            if ($model instanceof Expense) {
                CreateBankAccountTransaction::dispatch($model, !$model->action);
                CreateAccountStatement::dispatch($model, $model->amount, $model->action);
            }
        }

        if ($this->getApprovalStatus() == ApprovalStatus::REVERSED) {
            if ($model instanceof BankAccountAdjustment) {
                $model->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, !$model->action));
            }

            if ($model instanceof Invoice) {
                CreateAccountStatement::dispatch($model, $model->amount, !$model->action);
            }
            if ($model instanceof Payment) {
                CreateBankAccountTransaction::dispatch($model, !$model->action);
                CreateAccountStatement::dispatch($model, $model->amount, $model->action);
            }

            if ($model instanceof Expense) {
                CreateBankAccountTransaction::dispatch($model, $model->action);
                CreateAccountStatement::dispatch($model, $model->amount, !$model->action);
            }
        }
    }
}
