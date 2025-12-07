<?php

namespace Modules\Tenants\Repositories;

interface TenantRepositoryInterface
{
    public function createTenant(array $data);
    public function createDomain($tenant, string $domain);
    public function createRoles();
    public function createAdmin(array $data);
    public function getAll();
}
