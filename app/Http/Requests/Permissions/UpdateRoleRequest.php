<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Validation\Rule;

class UpdateRoleRequest extends StoreRoleRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignoreModel($this->role)
            ],
        ];
    }
}
