<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tenant Login
     */

    public function me(Request $request)
    {
        return response()->json([
            'user' => [
                'id'     => $request->user()->id,
                'name'   => $request->user()->name,
                'email'  => $request->user()->email,
                'roles'  => $request->user()->roles()->pluck('name'),
            ],
            'tenant_id' => tenant('id'),
        ]);
    }

    public function login(Request $request)
    {
        
        // INPUT validasiya
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // İstifadəçini tapırıq
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email və ya şifrə yanlışdır'
            ], 401);
        }

        // Köhnə tokenləri silirik (təhlükəsizlik üçün)
        $user->tokens()->delete();

        // Yeni token yaradırıq
        $token = $user->createToken('tenant_token')->plainTextToken;

        return response()->json([
            'message'   => 'Uğurla daxil oldunuz.',
            'user' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'roles'  => $user->roles()->pluck('name'),
            ],
            'token'     => $token,
            'tenant_id' => tenant('id'),
        ]);
    }


    /**
     * Tenant Logout
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Uğurla çıxış etdiniz.'
        ]);
    }
}

