<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pin' => $this->pin,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}
