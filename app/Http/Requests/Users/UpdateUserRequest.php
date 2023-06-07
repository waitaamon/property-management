<?php

namespace App\Http\Requests\Users;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => ['required', 'array'],
            'password' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignoreModel($this->user)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignoreModel($this->user)],
        ];
    }
}
