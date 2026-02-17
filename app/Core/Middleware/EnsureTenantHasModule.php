<?php

namespace App\Core\Middleware;

use App\Core\Context\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantHasModule
{
    /**
     * Proverava da li tenant ima aktiviran traženi modul.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        /** @var TenantContext $context */
        $context = app(TenantContext::class);

        if (! $context->hasTenant()) {
            abort(403, 'Tenant nije izabran.');
        }

        if (! $context->hasModule($module)) {
            abort(403, 'Ovaj modul nije aktiviran za trenutni tenant.');
        }

        return $next($request);
    }
}
