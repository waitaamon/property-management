<?php

namespace App\Http\Resources;

use App\Http\Resources\Properties\PropertyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Permissions\RoleResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            'is_active' => !$this->resource->trashed(),
            'created_at' => $this->whenHas('created_at'),

            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'properties' => PropertyResource::collection($this->whenLoaded('properties')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
            ]
        ];
    }
}
