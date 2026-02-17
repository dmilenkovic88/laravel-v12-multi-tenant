<?php

namespace App\Core\Tenancy\Actions;

use App\Core\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

class InvalidateTenantModulesCacheAction
{
    public function execute(int $tenantId): void
    {
        Cache::forget(CacheKeys::tenantModuleSet($tenantId));
    }
}
