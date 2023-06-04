<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountStatementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->whenHas('code'),
            'amount' => $this->whenHas('amount'),
            'action' => $this->whenHas('action'),
            'balance' => $this->whenHas('balance'),
            'causer' => $this->whenAppended('causer'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'accountable' => $this->whenLoaded('accountable', fn() => $this->accountable),
            'statementable' => $this->whenLoaded('statementable', fn() => $this->statementable),
        ];
    }
}
