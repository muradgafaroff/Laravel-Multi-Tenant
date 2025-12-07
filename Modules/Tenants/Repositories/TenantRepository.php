<?php

namespace Modules\Tenants\Repositories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantRepository implements TenantRepositoryInterface
{
    public function createTenant(array $data)
    {
        return Tenant::create([
            'id' => $data['subdomain'],
            'data' => ['company_name' => $data['subdomain']]
        ]);
    }

    public function createDomain($tenant, string $domain)
    {
        return $tenant->domains()->create([
            'domain' => $domain
        ]);
    }

    public function createRoles()
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'guard_name' => 'sanctum'],
            ['name' => 'manager', 'guard_name' => 'sanctum'],
            ['name' => 'employee', 'guard_name' => 'sanctum'],
        ]);
    }

    public function createAdmin(array $data)
    {
        $admin = User::create([
            'name' => 'Company Admin',
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $admin->roles()->attach(1);

        return $admin;
    }

    public function getAll()
    {
        return Tenant::all();
    }
}
