<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'role' => ['nullable', 'string'], 
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ad daxil edilməlidir.',
            'email.required' => 'Email daxil edilməlidir.',
            'email.unique' => 'Bu email artıq qeydiyyatdadır.',
            'password.required' => 'Şifrə daxil edilməlidir.',
            'password.min' => 'Şifrə minimum 6 simvol olmalıdır.',
        ];
    }
}
