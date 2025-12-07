<?php

namespace Modules\Tenants\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class TenantRegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validate
        $request->validate([
            'subdomain' => 'required|string|unique:tenants,id',
            'admin_email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // 2. Create Tenant in central DB
        $tenant = Tenant::create([
            'id' => $request->subdomain,
            'data' => [
                'company_name' => $request->subdomain,
            ],
        ]);

        // 3. Create domain
        $tenant->domains()->create([
            'domain' => $request->subdomain . '.localapp.com',
        ]);

        // 4. ACTIVATE tenant DB
        tenancy()->initialize($tenant);

        // 5. Run tenant migrations
        Artisan::call('migrate', [
            '--path' => 'database/migrations',
            '--force' => true,
        ]);

        // 6. Create roles
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'manager'],
            ['name' => 'employee'],
        ]);

        // 7. Create admin user
        $admin = User::create([
            'name' => 'Company Admin',
            'email' => $request->admin_email,
            'password' => Hash::make($request->password),
        ]);

        // 8. Assign role to admin
        $admin->roles()->attach(1);

        return response()->json([
            'message' => 'Tenant yaradıldı!',
            'url' => 'http://' . $request->subdomain . '.localapp.com/api/login'
        ], 201);
    }

}
