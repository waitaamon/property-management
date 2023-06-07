<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateBankAccountTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Model $model, protected bool $action = true)
    {
    }

    public function handle(): void
    {
        $balance = $this->model->bankAccount->balance;

        $this->model->transactions()->create([
            'action' => $this->action,
            'account_id' => $this->model->bankAccount->id,
            'amount' => $amount = abs($this->model->amount),
            'balance' => $this->action ? $balance + $amount : $balance - $amount,
        ]);
    }
}
