<?php

namespace App\Http\Requests\Expenses;

use Illuminate\Validation\Rule;

class UpdateExpenseCategoryRequest extends StoreExpenseCategoryRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => ['required', 'string', 'max:255', Rule::unique('expense_categories')->ignoreModel($this->expense_category)]
        ];
    }
}
