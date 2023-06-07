<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Enums\ApprovalStatus;
use App\Models\Sales\SaleOrder;
use App\Models\Payments\Payment;
use App\Models\Expenses\Expense;
use App\Models\Suppliers\Supplier;
use App\Models\Products\Purchase;
use Illuminate\Queue\SerializesModels;
use App\Models\CreditNotes\CreditNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\BankAccounts\BankAccountAdjustment;

class UpdateModelApprovalAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Model $model, protected User $user, protected ApprovalStatus $status, protected string $note)
    {
    }

    public function handle(): void
    {
        $this->model->approvals()->create([
            'note' => $this->note,
            'user_id' => $this->user->id,
            'status' =>  $this->status->value
        ]);

        $this->CreateAccountStatement();

        $this->createBankAccountTransaction();

        $this->model->update(['status' => $this->status->value]);

    }

    public function CreateAccountStatement(): void
    {
        if ($this->status == ApprovalStatus::APPROVED) {
            match (true) {
                $this->model instanceof SaleOrder => CreateAccountStatement::dispatch($this->model, $this->model->total_amount),
                $this->model instanceof Payment => CreateAccountStatement::dispatch($this->model, $this->model->amount, $this->model->accountable instanceof Supplier),
                $this->model instanceof CreditNote, $this->model instanceof Purchase, $this->model instanceof Expense => CreateAccountStatement::dispatch($this->model, $this->model->amount, false),
                default => null
            };
        }

        if ( $this->status == ApprovalStatus::REVERSED) {
            match (true) {
                $this->model instanceof SaleOrder => CreateAccountStatement::dispatch($this->model, $this->model->total_amount, false),
                $this->model instanceof Payment => CreateAccountStatement::dispatch($this->model, $this->model->amount, !$this->model->accountable instanceof Supplier),
                $this->model instanceof CreditNote, $this->model instanceof Purchase, $this->model instanceof Expense => CreateAccountStatement::dispatch($this->model, $this->model->amount),
            };
        }
    }

    protected function createBankAccountTransaction(): void
    {
        if ($this->status == ApprovalStatus::APPROVED) {
            match (true) {
                $this->model instanceof Payment => CreateBankAccountTransaction::dispatch($this->model),
                $this->model instanceof BankAccountAdjustment => $this->model->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, $item->action)),
                default => null,
            };
        }

        if ($this->status == ApprovalStatus::REVERSED) {
            match (true) {
                $this->model instanceof Payment => CreateBankAccountTransaction::dispatch($this->model, false),
                $this->model instanceof BankAccountAdjustment => $this->model->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, !$item->action)),
            };
        }
    }
}
