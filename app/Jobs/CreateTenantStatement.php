<?php

namespace App\Jobs;

use App\Models\Tenants\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateTenantStatement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Model $model, protected Tenant $tenant, protected int $amount, protected string $description = 'invoice', protected bool $action = true)
    {
        //
    }

    public function handle(): void
    {
        $this->model->statements()->create([
            'amount' => $this->amount,
            'action' => $this->action,
            'tenant_id' => $this->tenant->id,
            'description' => $this->description,
            'balance' => $this->getTenantBalance(),
        ]);
    }

    protected function getTenantBalance():int
    {
        return $this->action ? $this->tenant->balance + $this->amount : $this->tenant->balance - $this->amount;
    }
}
