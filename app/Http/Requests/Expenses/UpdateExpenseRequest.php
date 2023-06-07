<?php

namespace App\Http\Requests\Expenses;

class UpdateExpenseRequest extends StoreExpenseRequest
{
    public function rules(): array
    {
        return [parent::rules()];
    }
}
