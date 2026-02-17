<?php

namespace App\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Proverava da li je ulogovani korisnik aktivan.
     *
     * Ako korisnik nije aktivan, prekida se izvršavanje sa 403 greškom.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Ako nema ulogovanog korisnika, samo nastavi
        // (auth middleware će rešiti pristup)
        if (! $user) {
            return $next($request);
        }

        // Ako korisnik nije aktivan, abortiraj sa 403
        if (! $user->active) {
            abort(403, 'Vaš nalog nije aktivan.');
        }

        return $next($request);
    }
}
