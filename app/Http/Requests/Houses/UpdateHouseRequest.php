<?php

namespace App\Http\Requests\Houses;

class UpdateHouseRequest extends StoreHouseRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules()
        ];
    }
}
