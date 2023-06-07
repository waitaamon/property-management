<?php

namespace App\Http\Requests\Properties;

use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends StorePropertyRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => ['required', 'string', 'max:255', Rule::unique('properties')->ignoreModel($this->property)]
        ];
    }
}
