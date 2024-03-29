<?php

namespace App\Http\Resources\Permissions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
       return [
           'id' => $this->id,
           'name' => $this->name,
           'permissions' => PermissionResource::collection($this->whenLoaded('permissions'))
       ];
    }
}
