<?php

namespace App\Providers;

use App\Core\Support\Listeners\LogAuthActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Mapiranje događaja na listenere.
     *
     * @var array<class-string, array<int, array{0: class-string, 1: string}>>
     */
    protected $listen = [
        Login::class => [
            [LogAuthActivity::class, 'handleLogin'],
        ],
        Logout::class => [
            [LogAuthActivity::class, 'handleLogout'],
        ],
        Failed::class => [
            [LogAuthActivity::class, 'handleFailed'],
        ],
    ];
}
