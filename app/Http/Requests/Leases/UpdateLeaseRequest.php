<?php

namespace App\Http\Requests\Leases;

class UpdateLeaseRequest extends StoreLeaseRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules()
        ];
    }
}
