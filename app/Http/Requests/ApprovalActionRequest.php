<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'note' => ['required', 'string'],
            'model' => ['required', 'string'],
            'action' => ['required', 'string'],
        ];
    }
}
