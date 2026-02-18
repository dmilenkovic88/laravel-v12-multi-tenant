<?php

namespace App\Core\Middleware;

use App\Core\Context\TenantContext;
use App\Core\Tenancy\Actions\ClearInvalidDefaultTenantAction;
use App\Core\Tenancy\Actions\GetTenantModuleSetAction;
use App\Core\Tenancy\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetTenantDatabase
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        /** @var Tenant|null $tenant */
        $tenant = $request->attributes->get('tenant');

        // Ako tenant nije već validiran kroz tenant.access middleware
        if (! $tenant) {

            $tenantId = session('tenant_id');

            if (! $tenantId || ! $user) {
                return $next($request);
            }

            $tenant = Tenant::query()
                ->active()
                ->whereKey((int) $tenantId)
                ->first();

            // Tenant ne postoji ili nije aktivan
            if (! $tenant) {

                // Ako je ovo bio default tenant – očisti ga
                if ((int) $user->default_tenant_id === (int) $tenantId) {
                    app(ClearInvalidDefaultTenantAction::class)->execute($user);
                } else {
                    Session::forget('tenant_id');
                }

                return $next($request);
            }

            // Ako user nije super_admin, proveri pivot pristup
            if ($user->type !== 'super_admin') {

                $hasAccess = $tenant->users()
                    ->whereKey($user->id)
                    ->exists();

                if (! $hasAccess) {

                    if ((int) $user->default_tenant_id === (int) $tenantId) {
                        app(ClearInvalidDefaultTenantAction::class)->execute($user);
                    } else {
                        Session::forget('tenant_id');
                    }

                    return $next($request);
                }
            }
        }

        // Tenant je validan — sada menjamo konekciju
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
