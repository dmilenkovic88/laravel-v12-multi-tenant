<?php

namespace App\Core\Tenancy\Actions;

use App\Core\Support\Activity\ActivityLogger; // ADDED
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SwitchTenantAction
{
    // ADDED: Inject ActivityLogger
    public function __construct(
        private readonly ActivityLogger $logger,
    ) {}

    /**
     * Menja tenant u session-u. Prazno = rad bez tenant-a.
     * Bezbednost: proverava da user ima pristup tenant-u (osim super_admin).
     */
    public function execute(User $user, ?int $tenantId): void
    {
        // ADDED: zapamti prethodni tenant
        $fromTenantId = session('tenant_id') ? (int) session('tenant_id') : null;

        // "Bez tenanta"
        if (! $tenantId) {
            Session::forget('tenant_id');

            // ADDED: log switch na null
            $this->logger->log('tenant.switch', null, [
                'from_tenant_id' => $fromTenantId,
                'to_tenant_id' => null,
            ], tenantId: $fromTenantId);

            return;
        }

        $tenantId = (int) $tenantId;

        $tenant = Tenant::query()
            ->active()
            ->whereKey($tenantId)
            ->first();

        if (! $tenant) {
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

        // ADDED: log uspešan switch tenant-a
        $this->logger->log('tenant.switch', $tenant, [
            'from_tenant_id' => $fromTenantId,
            'to_tenant_id' => $tenant->id,
        ], tenantId: $tenant->id);
    }
}
