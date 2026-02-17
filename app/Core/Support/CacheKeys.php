<?php

namespace App\Core\Support;

class CacheKeys
{
    public static function userTenantsList(int $userId): string
    {
        return "user:{$userId}:tenants:list";
    }

    public static function tenantModuleSet(int $tenantId): string
    {
        return "tenant:{$tenantId}:modules:set";
    }
}
