<?php

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'user_name' => ['nullable', 'string', 'max:255', 'unique:users,user_name'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ];
    }
}
