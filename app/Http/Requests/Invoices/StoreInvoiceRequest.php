<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tenant' => ['required', 'integer', 'exists:tenants,id'],
            'lease' => ['required', 'integer', 'exists:leases,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'from'=> ['required', 'date'],
            'to'=> ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
