<?php

namespace App\Core\Support\Activity;

use App\Core\Context\TenantContext;
use App\Core\Support\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function __construct(
        private readonly TenantContext $tenantContext,
        private readonly Request $request,
    ) {}

    /**
     * Upis aktivnosti u landlord bazu.
     *
     * @param  string      $action  npr. 'tenant.switch', 'module.enable', 'auth.login'
     * @param  Model|null  $subject entitet nad kojim se radi (opciono)
     * @param  array       $meta    dodatni podaci
     * @param  int|null    $tenantId override tenant-a (opciono)
     */
    public function log(string $action, ?Model $subject = null, array $meta = [], ?int $tenantId = null): void
    {
        $user = $this->request->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'tenant_id' => $tenantId ?? $this->tenantContext->tenantId(),
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'meta' => array_filter([
                ...$meta,
                'ip' => $this->request->ip(),
                'ua' => substr((string) $this->request->userAgent(), 0, 500),
            ], fn ($v) => $v !== null && $v !== ''),
        ]);
    }
}
