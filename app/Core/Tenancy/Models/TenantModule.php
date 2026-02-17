<?php

namespace App\Core\Tenancy\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class TenantModule extends Pivot
{
    protected $connection = 'landlord';
    protected $table = 'tenant_modules';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        // Preporuka: samo expires_at, ostalo kroz attach/sync
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Da li je modul aktivan za tenant (po isteku).
     */
    public function isActive(): bool
    {
        $expiresAt = $this->expires_at;

        if (! $expiresAt) {
            return true;
        }

        return $expiresAt->isFuture();
    }
}
