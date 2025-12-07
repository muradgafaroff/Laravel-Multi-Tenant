<?php

namespace Modules\Tenants\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Tenants\Http\Requests\TenantRegisterRequest;
use Modules\Tenants\Services\TenantServiceInterface;

class TenantRegisterController extends Controller
{
    protected TenantServiceInterface $tenantService;

    public function __construct(TenantServiceInterface $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function register(TenantRegisterRequest $request)
    {
        $tenantUrl = $this->tenantService->registerTenant($request->validated());

        return response()->json([
            'message' => 'Tenant yaradıldı!',
            'url' => $tenantUrl
        ], 201);
    }
}
