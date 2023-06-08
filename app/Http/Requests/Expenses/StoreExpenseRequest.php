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
            'amount' => ['required', 'numeric', 'min:0'],
            'account' => ['required', 'integer', 'exists:bank_accounts,id'],
            'category' => ['required', 'integer', 'exists:expense_categories,id'],
        ];
    }
}
