<?php

namespace App\Http\Resources\Deposits;

use Illuminate\Http\Request;
use App\Http\Resources\Leases\LeaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->whenHas('amount'),
            'created_at' => $this->whenHas('created_at'),
            'refund_date' => $this->whenHas('refund_date'),

            'lease' => new LeaseResource($this->whenLoaded('lease'))
        ];
    }
}
