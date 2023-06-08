<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Expenses\Expense;
use App\Models\Invoices\Invoice;
use App\Models\Payments\Payment;
use Illuminate\Queue\SerializesModels;
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

    protected function getAccountableModel()
    {
        return match (true) {
             $this->model instanceof Payment => $this->model->tenant,
            $this->model instanceof Expense => $this->model->category,
            $this->model instanceof Invoice => $this->model->lease->tenant,
        };
    }
}
