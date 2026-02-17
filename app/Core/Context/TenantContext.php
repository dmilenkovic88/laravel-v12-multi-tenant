<?php

namespace App\Core\Context;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class TenantContext
{
    public function __construct(
        public readonly ?User $user,
        private ?Tenant $tenant = null,
        private array $moduleSet = [], // ['tasks' => true, ...]
    ) {}

    public function tenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function tenantId(): ?int
    {
        return $this->tenant?->id;
    }

    public function hasTenant(): bool
    {
        return (bool) $this->tenant;
    }

    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    /**
     * Set modula za brzu proveru (@module mora biti O(1)).
     */
    public function setModuleSet(array $moduleSet): void
    {
        $this->moduleSet = $moduleSet;
    }

    public function hasModule(string $slug): bool
    {
        return isset($this->moduleSet[$slug]);
    }
}
