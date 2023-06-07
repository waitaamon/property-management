<?php

namespace App\Http\Resources\Permissions;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'guard_name' => $this->whenHas('guard_name'),
            'users_count' => $this->whenHas('users_count'),
            'permissions_count' => $this->whenHas('permissions_count'),

            'users' => UserResource::collection($this->whenLoaded('users')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions'))
        ];
    }
}
