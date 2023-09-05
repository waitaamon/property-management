<?php

namespace App\Http\Resources\Invoices;

use App\Http\Resources\Deposits\DepositResource;
use App\Http\Resources\Goodwill\GoodwillResource;
use App\Http\Resources\Rent\RentResource;
use App\Models\Deposit;
use App\Models\Goodwill;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Http\Resources\TaxResource;
use App\Http\Resources\Tenants\TenantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'to' => $this->whenHas('to'),
            'code' => $this->whenHas('code'),
            'from' => $this->whenHas('from'),
            'note' => $this->whenHas('note'),
            'amount' => $this->whenHas('amount'),
            'status' => $this->whenHas('status'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'causer' => $this->whenLoaded('invoiceable', fn() => $this->causer),

            'tax' => new TaxResource($this->whenLoaded('tax')),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'invoiceable' => $this->whenLoaded('invoiceable', function () {
                return match (true) {
                    $this->invoiceable instanceof Rent => new RentResource($this->invoiceable->load('lease.house')),
                    $this->invoiceable instanceof Deposit => new DepositResource($this->invoiceable->load('lease.house')),
                    $this->invoiceable instanceof Goodwill => new GoodwillResource($this->invoiceable->load('lease.house')),
                };
            }),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
                'approve' => auth()->user()->can('approve', $this->resource),
                'reverse' => auth()->user()->can('reverse', $this->resource),
                'print' => auth()->user()->can('print', $this->resource),
            ]
        ];
    }
}
