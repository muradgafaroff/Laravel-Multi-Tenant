<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255', // örn: tenant1.localapp.test
        ]);

        // unik tenant id
        $id = Str::slug($request->company_name) . '-' . Str::random(6);

        // central DB'ye tenant kaydı
        $tenant = Tenant::create([
            'id' => $id,
        ]);

        // domain ekle
        $tenant->domains()->create([
            'domain' => $request->domain,
        ]);

        // Tenant context başlat
        tenancy()->initialize($tenant);

        // Tenant DB'ye tenant-specific migration'ları çalıştır
        // Varsayılan: tenant migration dosyalarını database/migrations/tenants altında tut
        Artisan::call('migrate', [
            '--path' => '/database/migrations/tenants',
            '--force' => true,
        ]);

        // Opt: tenant seed çalıştır
        Artisan::call('db:seed', [
            '--class' => 'TenantDatabaseSeeder',
            '--force' => true,
        ]);

        // Tenant context'i bitir
        tenancy()->end();

        return response()->json([
            'message' => 'Tenant created',
            'tenant_id' => $tenant->id,
        ], 201);
    }
}
