<?php

namespace App\Core\Middleware;

use App\Core\Tenancy\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    /**
     * Proverava da li korisnik ima pristup izabranom tenant-u.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $tenantId = session('tenant_id');

        if (! $tenantId) {
            abort(403, 'Nije izabran tenant.');
        }

        $tenant = Tenant::query()
            ->active()
            ->whereKey((int) $tenantId)
            ->first();

        if (! $tenant) {
            abort(404, 'Tenant ne postoji ili nije aktivan.');
        }

        // Super admin ima pristup svemu
        if ($user->type !== 'super_admin') {
            $hasAccess = $tenant->users()
                ->whereKey($user->id)
                ->exists();

            if (! $hasAccess) {
                abort(403, 'Nemate pristup ovom tenant-u.');
            }
        }

        // Prosledimo Tenant dalje (da SetTenantDatabase ne radi dodatni query)
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
