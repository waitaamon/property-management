<?php

namespace App\Http\Resources\Invoices;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\Leases\LeaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'to' => $this->whenHas('to'),
            'code' => $this->whenHas('code'),
            'from' => $this->whenHas('from'),
            'note' => $this->whenHas('note'),
            'amount' => $this->whenHas('amount'),
            'status' => $this->whenHas('status'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'user' => new UserResource($this->whenLoaded('user')),
            'lease' => new LeaseResource($this->whenLoaded('lease')),

            'can' => [
                'view' => auth()->user()->can('view', $this->resource),
                'edit' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
                'approve' => auth()->user()->can('approve', $this->resource),
                'reverse' => auth()->user()->can('reverse', $this->resource),
                'print' => auth()->user()->can('print', $this->resource),
            ]
        ];
    }
}
