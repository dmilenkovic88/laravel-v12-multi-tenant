<?php

namespace App\Core\Providers;

use App\Core\Context\TenantContext;
use App\Core\Tenancy\Actions\GetTenantModuleSetAction;
use App\Core\Tenancy\Resolvers\SessionTenantResolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(TenantContext::class, function ($app) {
            $request = $app['request'];
            $user = $request->user();

            $context = new TenantContext(user: $user);

            if (! $user) {
                return $context;
            }

            $tenant = $app->make(SessionTenantResolver::class)->resolve($user);
            $context->setTenant($tenant);

            if ($tenant) {
                $moduleSet = $app->make(GetTenantModuleSetAction::class)->execute($tenant);
                $context->setModuleSet($moduleSet);
            }

            return $context;
        });
    }

    public function boot(): void
    {
        /**
         * @module('tasks')
         * Prikazuje sadržaj samo ako je tenant iz session-a setovan i ima aktivan modul.
         */
        Blade::if('module', function (string $slug): bool {
            /** @var TenantContext $context */
            $context = app(TenantContext::class);

            return $context->hasTenant() && $context->hasModule($slug);
        });

        View::composer('*', function ($view) {
            $context = app(TenantContext::class);

            $view->with('appDisplayName',
                $context->tenant()?->name ?? config('app.name')
            );
        });

    }
}
