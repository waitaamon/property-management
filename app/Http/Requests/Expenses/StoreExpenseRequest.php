<?php

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => ['required', 'string'],
            'has_payment' => ['required', 'boolean'],
            'amount' => ['required', 'numeric', 'min:0'],
            'supplier' => ['required', 'integer', 'exists:suppliers,id'],
            'category' => ['required', 'integer', 'exists:expense_categories,id'],
            'account' => ['nullable', 'required_if:is_paid,true', 'integer', 'exists:bank_accounts,id'],
        ];
    }
}
