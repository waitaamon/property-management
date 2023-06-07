<?php

namespace App\Http\Requests\Payments;

class UpdatePaymentRequest extends StorePaymentRequest
{
    public function rules(): array
    {
        return [...parent::rules()];
    }
}
