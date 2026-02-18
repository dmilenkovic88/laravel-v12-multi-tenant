<?php

namespace App\Core\Support\Listeners;

use App\Core\Support\Activity\ActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthActivity
{
    public function __construct(
        private readonly ActivityLogger $logger,
    ) {}

    public function handleLogin(Login $event): void
    {
        $this->logger->log('auth.login', null, [
            'user_id' => $event->user->id,
        ], tenantId: null);
    }

    public function handleLogout(Logout $event): void
    {
        $this->logger->log('auth.logout', null, [
            'user_id' => $event->user?->id,
        ], tenantId: null);
    }

    /**
     * Neuspešan login pokušaj.
     * VAŽNO: Ne logovati lozinku.
     */
    public function handleFailed(Failed $event): void
    {
        $credentials = (array) $event->credentials;

        // U praksi je najčešće 'email' ili 'username'
        $identifier = $credentials['email']
            ?? $credentials['username']
            ?? $credentials['login']
            ?? null;

        $this->logger->log('auth.failed', null, array_filter([
            'identifier' => is_string($identifier) ? $identifier : null,
            'user_id' => $event->user?->id, // može biti null
        ]), tenantId: null);
    }
}
