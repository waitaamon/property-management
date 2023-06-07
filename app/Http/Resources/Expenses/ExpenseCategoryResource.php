<?php

namespace App\Http\Resources\Expenses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'is_active' => !$this->resource->trashed(),
            'created_at' => $this->whenHas('created_at'),
            'description' => $this->whenHas('description'),
            'total_amount' => $this->whenAppended('total_amount'),

            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
            ]
        ];
    }
}
