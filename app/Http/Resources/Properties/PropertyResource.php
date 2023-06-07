<?php

namespace App\Http\Resources\Properties;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            'phone' => $this->whenHas('phone'),
            'address' => $this->whenHas('address'),
            'location' => $this->whenHas('location'),
            'created_at' => $this->whenHas('created_at'),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
                'restore' => auth()->user()->can('restore', $this->resource),
            ]
        ];
    }
}
