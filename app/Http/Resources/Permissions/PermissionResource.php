<?php

namespace App\Http\Resources\Permissions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->whenHas('guard_name'),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'groups' => PermissionGroupResource::collection($this->whenLoaded('groups')),
        ];
    }
}
