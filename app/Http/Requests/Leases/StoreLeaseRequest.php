<?php

namespace App\Http\Requests\Leases;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant' => ['required', 'integer', 'exists:tenants,id'],
            'house' => ['required', 'integer', 'exists:houses,id'],
            'property' => ['required', 'integer', 'exists:properties,id'],
            'start_date' => ['required', 'date',],
            'end_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
