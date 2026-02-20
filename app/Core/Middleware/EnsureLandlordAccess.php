<?php

namespace App\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLandlordAccess
{
    /**
     * Dozvoljen pristup samo super_admin korisnicima.
     * Landlord rute ne zavise od tenant konekcije.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if ($user->type !== 'super_admin') {
            abort(403, 'Nemate pristup admin delu aplikacije.');
        }

        return $next($request);
    }
}
