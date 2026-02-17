<?php

namespace App\Core\Tenancy\Models;

use App\Core\Models\BaseLandlordModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Tenant extends BaseLandlordModel
{
    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'domain',
        'database_name',
        'database_host',
        'database_port',
        'database_username',
        'database_password',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'database_password' => 'encrypted', // ✅ važno
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'tenant_modules')
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
