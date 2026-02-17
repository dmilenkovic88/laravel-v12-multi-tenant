<?php

namespace App\Core\Tenancy\Actions;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class GetAvailableTenantsForUserAction
{
    public function execute(User $user): array
    {
        $cacheKey = "user:{$user->id}:tenants:list";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            $query = Tenant::query()->active()->orderBy('name');

            if ($user->type !== 'super_admin') {
                $query->whereHas('users', fn ($q) => $q->where('users.id', $user->id));
            }

            return $query
                ->get(['id', 'name'])
                ->map(fn ($t) => ['id' => $t->id, 'name' => $t->name])
                ->all();
        });
    }
}
