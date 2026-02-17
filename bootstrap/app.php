<?php

use \App\Core\Middleware\EnsureTenantAccess;
use \App\Core\Middleware\EnsureTenantHasModule;
use \App\Core\Middleware\SetTenantDatabase;
use App\Core\Middleware\EnsureUserIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'active' => EnsureUserIsActive::class,
            'tenant.access' => EnsureTenantAccess::class,
            'tenant.db' => SetTenantDatabase::class,
            'tenant.module' => EnsureTenantHasModule::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
