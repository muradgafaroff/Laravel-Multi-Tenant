<?php

namespace Modules\Users\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception("Email və ya şifrə yanlışdır", 401);
        }

        // Köhnə tokenləri sil
        $user->tokens()->delete();

        $token = $user->createToken('tenant_token')->plainTextToken;

        return [
            'message' => 'Uğurla daxil oldunuz.',
            'user' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'roles'  => $user->roles()->pluck('name'),
            ],
            'token'     => $token,
            'tenant_id' => tenant('id'),
        ];
    }

    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }

    public function me(): array
    {
        $user = auth()->user();

        return [
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'roles' => $user->roles()->pluck('name'),
            ],
            'tenant_id' => tenant('id'),
        ];
    }
}
