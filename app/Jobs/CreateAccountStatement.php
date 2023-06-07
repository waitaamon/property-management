<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Sales\SaleOrder;
use App\Models\Expenses\Expense;
use App\Models\Payments\Payment;
use App\Models\Products\Purchase;
use Illuminate\Queue\SerializesModels;
use App\Models\CreditNotes\CreditNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateAccountStatement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Model $model, protected float $amount, protected bool $action = true)
    {
    }

    public function handle(): void
    {
        $statement = $this->model->statements()->create([
            'amount' => $this->amount,
            'action' => $this->action,
            'balance' => $this->getAccountBalance(),
        ]);

        $account = $this->getAccountableModel();

        $statement->accountable()->associate($account);

        $statement->save();

    }

    protected function getAccountBalance(): float
    {
        $account = $this->getAccountableModel();

        return $this->action ? $account->balance + $this->amount : $account->balance - $this->amount;

    }

    protected function getAccountableModel(): Model|null
    {
        return match (true) {
            $this->model instanceof SaleOrder => $this->model->tenant,
            $this->model instanceof CreditNote => $this->model->tenant,
            $this->model instanceof Payment => $this->model->accountable,
            $this->model instanceof Purchase || $this->model instanceof Expense => $this->model->supplier,
            default => null
        };
    }
}
