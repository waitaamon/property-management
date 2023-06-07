<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Request;
use App\Models\Customers\Customer;
use App\Models\Suppliers\Vendor;
use App\Http\Resources\UserResource;
use App\Http\Resources\ApprovalResource;
use App\Http\Resources\Sales\SaleOrderResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BankAccounts\BankAccountResource;
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
            'bank_account' => new BankAccountResource($this->whenLoaded('bankAccount')),
            'approvals' => ApprovalResource::collection($this->whenLoaded('approvals')),
            'paymentable' => $this->whenLoaded('paymentable', fn() => $this->paymentable),
            'accountable' => $this->whenLoaded('accountable', fn() => $this->accountable),
            'statements' => AccountStatementResource::collection($this->whenLoaded('statements')),

            'account_type' => $this->whenLoaded('accountable', function () {
                return match (true) {
                    $this->accountable instanceof Customer => 'customer',
                    $this->accountable instanceof Vendor => 'supplier',
                    default => ''
                };
            }),

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
