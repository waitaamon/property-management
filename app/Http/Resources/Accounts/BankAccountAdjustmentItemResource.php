<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountAdjustmentItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'to' => $this->whenHas('to'),
            'from' => $this->whenHas('from'),
            'amount' => $this->whenHas('amount'),

            'account' => new BankAccountResource($this->whenLoaded('account')),
            'adjustment' => new BankAccountAdjustmentResource($this->whenLoaded('adjustment')),
        ];
    }
}
