<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->whenHas('code'),
            'amount' => $this->whenHas('amount'),
            'balance' => $this->whenHas('balance'),
            'action' => $this->whenHas('action'),
            'created_at' => $this->whenHas('created_at'),

            'account' => new BankAccountResource($this->whenLoaded('account')),
            'transactionable' => $this->whenLoaded('transactionable', fn() => $this->resource->transactionable)
        ];
    }
}
