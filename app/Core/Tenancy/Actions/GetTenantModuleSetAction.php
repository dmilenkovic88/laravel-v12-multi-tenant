<?php

namespace App\Core\Tenancy\Actions;

use App\Core\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\Cache;

class GetTenantModuleSetAction
{
    public function execute(Tenant $tenant): array
    {
        $cacheKey = "tenant:{$tenant->id}:modules:set";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($tenant) {
            // Napomena: ovde po potrebi dodaj logiku za expires_at (ako koristiš).
            $slugs = $tenant->modules()
                ->where('modules.active', true)
                ->pluck('modules.slug')
                ->all();

            return array_fill_keys($slugs, true);
        });
    }
}
