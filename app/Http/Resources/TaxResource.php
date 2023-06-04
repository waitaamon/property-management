<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'rate' => $this->whenHas('rate'),
            'is_default' => $this->whenHas('is_default'),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
            ]
        ];
    }
}
