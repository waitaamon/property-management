<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountAdjustmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->whenHas('code'),
            'status' => $this->whenHas('status'),
            'description' => $this->whenHas('description'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
