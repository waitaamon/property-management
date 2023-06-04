<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BankAccounts\BankAccountAdjustmentResource;

class BankAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'is_active' => !$this->resource->trashed(),
            'balance' => $this->whenAppended('balance'),
            'created_at' => $this->whenHas('created_at'),

            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'adjustments' => BankAccountAdjustmentResource::collection($this->whenLoaded('adjustments')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
            ]
        ];
    }
}
