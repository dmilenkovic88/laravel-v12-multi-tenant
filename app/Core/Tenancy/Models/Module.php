<?php

namespace App\Core\Tenancy\Models;

use App\Core\Models\BaseLandlordModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends BaseLandlordModel
{
    protected $table = 'modules';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_premium',
    ];

    /**
     * Kastovanje atributa.
     */
    protected $casts = [
        'is_premium' => 'boolean',
    ];

    /**
     * Svi tenanti koji koriste ovaj modul.
     */
    public function tenants(): BelongsToMany
    {
        // Pošto je Tenant u istom namespace-u, ne treba pun path,
        // ali koristimo ::class radi sigurnosti.
        return $this->belongsToMany(Tenant::class, 'tenant_modules');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }


}
