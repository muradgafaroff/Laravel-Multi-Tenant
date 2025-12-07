<?php

namespace Modules\Tenants\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tenants\Services\TenantServiceInterface;
use Illuminate\Http\Request;

class TenantsController extends Controller
{
    protected TenantServiceInterface $tenantService;

    public function __construct(TenantServiceInterface $tenantService)
    {
        $this->tenantService = $tenantService;
    }

   
    public function index()
    {
        return response()->json(
            $this->tenantService->listTenants()
        );
    }

   
    public function show($id)
    {
        return response()->json(
            $this->tenantService->getTenantById($id)
        );
    }

   
}
