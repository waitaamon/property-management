<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Enums\ApprovalStatus;
use App\Models\Expenses\Expense;
use App\Models\Payments\Payment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Accounts\BankAccountAdjustment;

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

        $this->createAccountStatement();

        $this->createBankAccountTransaction();

        $this->model->update(['status' => $this->status->value]);

    }

    public function CreateAccountStatement(): void
    {
        if ($this->status == ApprovalStatus::APPROVED) {
            match (true) {
                $this->model instanceof Expense => CreateAccountStatement::dispatch($this->model, $this->model->amount),
                $this->model instanceof Payment => CreateAccountStatement::dispatch($this->model, $this->model->amount, false),
                default => null
            };
        }

        if ( $this->status == ApprovalStatus::REVERSED) {
            match (true) {
                $this->model instanceof Payment => CreateAccountStatement::dispatch($this->model, $this->model->amount),
                $this->model instanceof Expense => CreateAccountStatement::dispatch($this->model, $this->model->amount, false),
            };
        }
    }

    protected function createBankAccountTransaction(): void
    {
        if ($this->status == ApprovalStatus::APPROVED) {
            match (true) {
                $this->model instanceof Payment => CreateBankAccountTransaction::dispatch($this->model),
                $this->model instanceof Expense => CreateBankAccountTransaction::dispatch($this->model, false),
                $this->model instanceof BankAccountAdjustment => $this->model->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, $item->action)),
                default => null,
            };
        }

        if ($this->status == ApprovalStatus::REVERSED) {
            match (true) {
                $this->model instanceof Expense => CreateBankAccountTransaction::dispatch($this->model),
                $this->model instanceof Payment => CreateBankAccountTransaction::dispatch($this->model, false),
                $this->model instanceof BankAccountAdjustment => $this->model->items->each(fn($item) => CreateBankAccountTransaction::dispatch($item, !$item->action)),
            };
        }
    }
}
