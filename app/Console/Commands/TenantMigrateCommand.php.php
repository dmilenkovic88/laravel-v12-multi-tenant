<?php

namespace App\Console\Commands;

use App\Core\Tenancy\Actions\RunTenantMigrationsAction;
use App\Core\Tenancy\Models\Tenant;
use Illuminate\Console\Command;

final class TenantMigrateCommand extends Command
{
    protected $signature = 'tenant:migrate {tenant}';
    protected $description = 'Run tenant migrations for a specific tenant';

    public function handle(RunTenantMigrationsAction $action): int
    {
        $tenant = Tenant::query()->findOrFail($this->argument('tenant'));

        $output = $action->execute($tenant);

        $this->line($output);

        return self::SUCCESS;
    }
}
