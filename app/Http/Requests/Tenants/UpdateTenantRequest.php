<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Validation\Rule;

class UpdateTenantRequest extends StoreTenantRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => ['required', 'string', 'max:255', Rule::unique('tenants')->ignoreModel($this->tenant)]
        ];
    }
}
