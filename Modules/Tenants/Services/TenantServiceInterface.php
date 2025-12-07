<?php

namespace Modules\Tenants\Services;

interface TenantServiceInterface
{
    public function registerTenant(array $data): string;

    public function listTenants();

}
