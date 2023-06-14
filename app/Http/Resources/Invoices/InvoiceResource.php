<?php

namespace App\Http\Resources\Invoices;

use App\Http\Resources\Accounts\AccountStatementResource;
use App\Http\Resources\ApprovalResource;
use App\Http\Resources\Houses\HouseResource;
use App\Http\Resources\TaxResource;
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
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'to' => $this->whenHas('to'),
            'code' => $this->whenHas('code'),
            'from' => $this->whenHas('from'),
            'note' => $this->whenHas('note'),
            'amount' => $this->whenHas('amount'),
            'status' => $this->whenHas('status'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'tax' => new TaxResource($this->whenLoaded('tax')),
            'user' => new UserResource($this->whenLoaded('user')),
            'lease' => new LeaseResource($this->whenLoaded('lease')),
            'houses' => new HouseResource($this->whenLoaded('houses')),
            'approvals' => ApprovalResource::collection($this->whenLoaded('approvals')),
            'statements' => AccountStatementResource::collection($this->whenLoaded('statements')),


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
