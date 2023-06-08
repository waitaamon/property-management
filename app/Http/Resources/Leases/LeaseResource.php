<?php

namespace App\Http\Resources\Leases;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ApprovalResource;
use App\Http\Resources\Houses\HouseResource;
use App\Http\Resources\Tenants\TenantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'note' => $this->whenHas('note'),
            'code' => $this->whenHas('code'),
            'state' => $this->whenHas('state'),
            'status' => $this->whenHas('status'),
            'end_date' => $this->whenHas('end_date'),
            'start_date' => $this->whenHas('start_date'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'user' => new UserResource($this->whenLoaded('user')),
            'house' => new HouseResource($this->whenLoaded('house')),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'approvals' => ApprovalResource::collection($this->whenLoaded('approvals')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
                'approve' => auth()->user()->can('approve', $this->resource),
                'terminate' => auth()->user()->can('terminate', $this->resource),
            ]
        ];
    }
}
