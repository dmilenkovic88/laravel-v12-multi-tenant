<?php

namespace App\Core\Tenancy\Resolvers;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class SessionTenantResolver
{
    public function resolve(?User $user): ?Tenant
    {
        if (! $user) {
            return null;
        }

        $tenantId = session('tenant_id');

        // Ako nema session, pokušaj default_tenant_id, ali tek posle provere prava
        if (! $tenantId && $user->default_tenant_id) {
            $candidateId = (int) $user->default_tenant_id;

            $tenant = Tenant::query()->active()->whereKey($candidateId)->first();

            if (! $tenant) {
                return null;
            }

            // Super admin ima pravo svuda
            if ($user->type === 'super_admin') {
                session(['tenant_id' => $tenant->id]);
                return $tenant;
            }

            // Proveri pivot pristup
            $hasAccess = $tenant->users()->whereKey($user->id)->exists();

            if ($hasAccess) {
                session(['tenant_id' => $tenant->id]);
                return $tenant;
            }

            // Nema pristup: NE setujemo session tenant
            // (opciono) možeš i obrisati default_tenant_id u bazi, vidi dole.
            return null;
        }

        if (! $tenantId) {
            return null;
        }

        return Tenant::query()->active()->whereKey((int) $tenantId)->first();
    }
}
