<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'bank_account' => ['required', 'integer', 'exists:bank_accounts,id'],
            'account_type' => ['required', 'string', 'in:tenant,supplier'],
            'account' => ['required', 'integer']
        ];
    }
}
