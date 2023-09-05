<?php

namespace App\Http\Resources\Goodwill;

use Illuminate\Http\Request;
use App\Http\Resources\Leases\LeaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodwillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->whenHas('amount'),
            'created_at' => $this->whenHas('created_at'),

            'lease' => new LeaseResource($this->whenLoaded('lease'))
        ];
    }
}
