<?php

namespace App\Core\Middleware;

use App\Core\Context\TenantContext;
use App\Core\Tenancy\Actions\GetTenantModuleSetAction;
use App\Core\Tenancy\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantDatabase
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Tenant|null $tenant */
        $tenant = $request->attributes->get('tenant');

        if (! $tenant) {
            $tenantId = session('tenant_id');

            if (! $tenantId) {
                return $next($request);
            }

            $tenant = Tenant::query()->active()->whereKey((int) $tenantId)->first();

            if (! $tenant) {
                abort(404, 'Tenant ne postoji ili nije aktivan.');
            }
        }

        config([
            'database.connections.tenant.host' => $tenant->database_host,
            'database.connections.tenant.port' => $tenant->database_port ?? config('database.connections.tenant.port'),
            'database.connections.tenant.database' => $tenant->database_name,
            'database.connections.tenant.username' => $tenant->database_username ?? config('database.connections.tenant.username'),
            'database.connections.tenant.password' => $tenant->database_password ?? config('database.connections.tenant.password'),
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        /** @var TenantContext $context */
        $context = app(TenantContext::class);
        $context->setTenant($tenant);

        $moduleSet = app(GetTenantModuleSetAction::class)->execute($tenant);
        $context->setModuleSet($moduleSet);

        return $next($request);
    }
}
