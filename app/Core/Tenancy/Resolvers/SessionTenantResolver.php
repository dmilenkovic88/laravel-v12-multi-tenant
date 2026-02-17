<?php

namespace App\Core\Tenancy\Resolvers;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class SessionTenantResolver
{
    /**
     * Tenant se identifikuje u session (tenant_id).
     * Ako tenant_id ne postoji, a user ima default_tenant_id, koristi njega.
     */
    public function resolve(?User $user): ?Tenant
    {
        if (! $user) {
            return null;
        }

        $tenantId = session('tenant_id');

        if (! $tenantId && $user->default_tenant_id) {
            $tenantId = $user->default_tenant_id;
            session(['tenant_id' => $tenantId]); // držimo session konzistentnim
        }

        if (! $tenantId) {
            return null;
        }

        return Tenant::query()->whereKey((int) $tenantId)->first();
    }
}
