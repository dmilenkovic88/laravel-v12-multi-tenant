<?php

namespace App\Core\Tenancy\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Session;

class ClearInvalidDefaultTenantAction
{
    /**
     * Briše default_tenant_id korisniku ako više nije validan
     * i čisti session tenant_id.
     */
    public function execute(User $user): void
    {
        if ($user->default_tenant_id !== null) {
            $user->forceFill([
                'default_tenant_id' => null,
            ])->save();
        }

        Session::forget('tenant_id');
    }
}
