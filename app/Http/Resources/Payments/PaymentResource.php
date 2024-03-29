<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ApprovalResource;
use App\Http\Resources\Tenants\TenantResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Accounts\BankAccountResource;
use App\Http\Resources\Accounts\TransactionResource;
use App\Http\Resources\Accounts\AccountStatementResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'note' => $this->whenHas('note'),
            'code' => $this->whenHas('code'),
            'amount' => $this->whenHas('amount'),
            'status' => $this->whenHas('status'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'user' => new UserResource($this->whenLoaded('user')),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'account' => new BankAccountResource($this->whenLoaded('bankAccount')),
            'approvals' => ApprovalResource::collection($this->whenLoaded('approvals')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
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
