<?php

namespace App\Http\Resources\Rent;

use App\Http\Resources\Leases\LeaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->whenHas('date'),
            'amount' => $this->whenHas('amount'),
            'created_at' => $this->whenHas('created_at'),

            'lease' => new LeaseResource($this->whenLoaded('lease'))
        ];
    }
}
