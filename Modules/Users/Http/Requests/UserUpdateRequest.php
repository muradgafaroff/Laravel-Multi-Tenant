<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Access control Controller
    }

    public function rules(): array
    {
        $id = $this->route('user'); 
        // Route::apiResource -> :user

        return [
            'name' => ['sometimes', 'string', 'max:190'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $id],
            'password' => ['sometimes', 'min:6'],
            'role' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Bu email artıq mövcuddur.',
            'password.min' => 'Şifrə minimum 6 simvol olmalıdır.',
        ];
    }
}
