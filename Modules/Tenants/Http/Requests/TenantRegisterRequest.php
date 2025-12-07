<?php

namespace Modules\Tenants\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'subdomain' => 'required|string|unique:tenants,id',
            'admin_email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
