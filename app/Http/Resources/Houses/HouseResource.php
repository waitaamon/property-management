<?php

namespace App\Http\Resources\Houses;

use App\Http\Resources\Properties\PropertyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'rent' => $this->whenHas('rent'),
            'deposit' => $this->whenHas('deposit'),
            'is_active' => $this->whenHas('is_active'),
            'created_at' => $this->whenHas('created_at'),
            'description' => $this->whenHas('description'),

            'property' => new PropertyResource($this->whenLoaded('property')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
                'restore' => auth()->user()->can('restore', $this->resource),
            ]
        ];
    }
}
