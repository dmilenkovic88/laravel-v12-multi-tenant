<?php

namespace App\Core\Tenancy\Actions;

use App\Core\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

class InvalidateUserTenantsCacheAction
{
    public function execute(int $userId): void
    {
        Cache::forget(CacheKeys::userTenantsList($userId));
    }
}
