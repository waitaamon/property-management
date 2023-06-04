<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'status' => $this->status,
            'created_at' => $this->created_at,

            'user' => new UserResource($this->whenLoaded('user')),
            'approveable' => $this->whenLoaded('approveable', fn() => $this->approveable)
        ];
    }
}
