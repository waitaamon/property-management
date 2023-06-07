<?php

namespace App\Http\Requests\Users;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => ['required', 'array'],
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(4),],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ];
    }
}
