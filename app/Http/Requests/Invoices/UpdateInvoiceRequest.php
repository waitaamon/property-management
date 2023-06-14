<?php

namespace App\Http\Requests\Invoices;

class UpdateInvoiceRequest extends StoreInvoiceRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules()
        ];
    }
}
