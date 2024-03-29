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
            'start_date' => ['required', 'date',],
            'notes' => ['nullable', 'string'],
        ];
    }
}
