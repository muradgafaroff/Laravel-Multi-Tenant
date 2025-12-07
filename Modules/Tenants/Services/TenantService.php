<?php

namespace Modules\Tenants\Services;

use Modules\Tenants\Repositories\TenantRepositoryInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantService implements TenantServiceInterface
{
    protected $repo;

    public function __construct(TenantRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function registerTenant(array $data): string
    {
        // -----------------------------------
        // 1. CENTRAL DATABASE TRANSACTION
        // -----------------------------------
        try {
            DB::connection(config('tenancy.central_database_connection'))->beginTransaction();

            // Create tenant record in central DB
            $tenant = $this->repo->createTenant($data);

            // Create domain in central DB
            $this->repo->createDomain($tenant, $data['subdomain'] . '.localapp.com');

            DB::connection(config('tenancy.central_database_connection'))->commit();
        } catch (\Throwable $e) {
            DB::connection(config('tenancy.central_database_connection'))->rollBack();
            throw $e;
        }

        // -----------------------------------
        // 2. TENANT DATABASE OPERATIONS
        // -----------------------------------
        try {
            // Switch into tenant DB
            tenancy()->initialize($tenant);

            // Run tenant migrations
            Artisan::call('migrate', [
                '--path' => 'database/migrations',
                '--force' => true,
            ]);

            // Create default roles
            $this->repo->createRoles();

            // Create admin user
            $this->repo->createAdmin([
                'email' => $data['admin_email'],
                'password' => $data['password']
            ]);

        } catch (\Throwable $e) {

            // ROLLBACK OPERATIONS
            // Tenant DB setup failed → remove tenant fully
            $tenant->domains()->delete();
            $tenant->delete();

            throw $e;
        }

        return "http://{$tenant->id}.localapp.com/api/login";
    }

    public function listTenants()
    {
        return $this->repo->getAll();
    }
}
