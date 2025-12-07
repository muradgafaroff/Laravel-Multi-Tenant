<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Users\Http\Requests\LoginRequest;
use Modules\Users\Services\AuthServiceInterface;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    public function login(LoginRequest $request)
    {
        try {
            $response = $this->auth->login($request->validated());
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function logout()
    {
        $this->auth->logout();
        return response()->json(['message' => 'Uğurla çıxış etdiniz.']);
    }

    public function me()
    {
        return response()->json($this->auth->me());
    }
}
