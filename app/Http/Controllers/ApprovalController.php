<?php

namespace App\Http\Controllers;

use Exception;
use App\Enums\ApprovalStatus;
use App\Models\Sales\SaleOrder;
use App\Models\Expenses\Expense;
use App\Models\Payments\Payment;
use App\Jobs\CreateStockMovement;
use App\Models\Products\Purchase;
use App\Models\CreditNotes\CreditNote;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\UpdateModelApprovalAction;
use App\Models\Products\ProductAdjustment;
use App\Http\Requests\ApprovalActionRequest;
use App\Models\BankAccounts\BankAccountAdjustment;
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
            'Payment' => Payment::findOrFail(request('id')),
            'Expense' => Expense::with('items')->findOrFail(request('id')),
            'SaleOrder' => SaleOrder::with('items.product')->findOrFail(request('id')),
            'Purchase' => Purchase::with('items.product')->findOrFail(request('id')),
            'ProductAdjustment' => ProductAdjustment::with('items.product')->findOrFail(request('id')),
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

        if ($model instanceof ProductAdjustment || $model instanceof SaleOrder || $model instanceof Purchase || $model instanceof CreditNote) {
            $model->items->each(fn($item) => CreateStockMovement::dispatch($item, $this->getApprovalStatus() == ApprovalStatus::APPROVED ? $item->action : !$item->action));
        }

        if ($model instanceof BankAccountAdjustment || $model instanceof Payment || $model instanceof Expense) {

        }
    }
}
