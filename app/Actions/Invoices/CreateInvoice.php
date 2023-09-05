<?php

namespace App\Actions\Invoices;

use App\Models\Tax;
use App\Models\Tenants\Tenant;
use App\Models\Invoices\Invoice;
use App\Jobs\CreateTenantStatement;
use Illuminate\Database\Eloquent\Model;

class CreateInvoice
{
    public static function handle(Model $model, Tenant $tenant, int $amount): Invoice|null
    {
        $tax = Tax::where('is_default', true)->first();

        if (!$tax) return null;

        return $model->invoice()->create([
            'amount' => $amount,
            'tax_id' => $tax->id,
            'tenant_id' => $tenant->id,
        ]);
    }
}
