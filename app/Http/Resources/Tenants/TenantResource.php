<?php

namespace App\Http\Resources\Tenants;

use App\Http\Resources\Houses\HouseResource;
use App\Http\Resources\Leases\LeaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pin' => $this->whenHas('pin'),
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            'is_active' => !$this->resource->trashed(),
            'phone' => $this->whenHas('phone'),
            'address' => $this->whenHas('address'),
            'balance' => $this->whenAppended('balance'),
            'created_at' => $this->whenHas('created_at'),

            'leases' => LeaseResource::collection($this->whenLoaded('leases')),
            'houses' => HouseResource::collection($this->whenLoaded('houses')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
                'restore' => auth()->user()->can('restore', $this->resource),
            ]
        ];
    }
}
