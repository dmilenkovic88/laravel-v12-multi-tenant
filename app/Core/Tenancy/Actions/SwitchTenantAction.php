<?php

namespace App\Core\Tenancy\Actions;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SwitchTenantAction
{
    /**
     * Menja tenant u session-u. Prazno = rad bez tenant-a.
     * Bezbednost: proverava da user ima pristup tenant-u (osim super_admin).
     */
    public function execute(User $user, ?int $tenantId): void
    {
        // "Bez tenanta"
        if (! $tenantId) {
            Session::forget('tenant_id');
            return;
        }

        $tenantId = (int) $tenantId;

        $tenant = Tenant::query()
            ->active()
            ->whereKey($tenantId)
            ->first();

        if (! $tenant) {
            // Čistimo session da ne ostane nevalidan tenant_id
            Session::forget('tenant_id');

            throw new HttpException(404, 'Tenant ne postoji ili nije aktivan.');
        }

        // Super admin ima pristup svemu
        if ($user->type !== 'super_admin') {
            $hasAccess = $tenant->users()
                ->whereKey($user->id)
                ->exists();

            if (! $hasAccess) {
                Session::forget('tenant_id');

                throw new HttpException(403, 'Nemate pristup izabranom tenant-u.');
            }
        }

        Session::put('tenant_id', $tenant->id);
    }
}
