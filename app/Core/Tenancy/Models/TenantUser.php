<?php

namespace App\Core\Tenancy\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantUser extends Pivot
{
    /**
     * Pivot tabela je u landlord bazi.
     */
    protected $connection = 'landlord';

    /**
     * Naziv pivot tabele.
     */
    protected $table = 'tenant_users';

    /**
     * Koristimo sopstveni ID u migraciji ($table->id()).
     */
    public $incrementing = true;

    /**
     * Pivot tabela ima created_at i updated_at.
     */
    public $timestamps = true;

    /**
     * Masovno dozvoljavamo menjanje samo role kolone.
     * FK vrednosti (tenant_id, user_id) vezujemo kroz attach/sync.
     */
    protected $fillable = [
        'role',
    ];

    /**
     * Definicije uloga radi konzistentnosti kroz sistem.
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OWNER = 'owner';
    public const ROLE_MEMBER = 'client';

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN], true);
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_MEMBER;
    }


}
