<?php

namespace App\Livewire\Layout;

use App\Core\Tenancy\Actions\GetAvailableTenantsForUserAction;
use App\Core\Tenancy\Actions\SwitchTenantAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TenantSwitcher extends Component
{
    public ?int $selectedTenantId = null;

    /** @var array<int, array{id:int, name:string}> */
    public array $tenants = [];

    public function mount(GetAvailableTenantsForUserAction $getTenants): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        // Učitavamo jednom (keširano kroz Action) da izbegnemo N+1 i nepotrebne rerendere.
        $this->tenants = $getTenants->execute($user);

        // Preferiramo session tenant, pa fallback na default_tenant_id (UI samo; session rešava resolver).
        $this->selectedTenantId = session('tenant_id')
            ? (int) session('tenant_id')
            : ($user->default_tenant_id ? (int) $user->default_tenant_id : null);
    }

    public function updatedSelectedTenantId($value, SwitchTenantAction $switch): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $tenantId = $value ? (int) $value : null;

        $switch->execute($user, $tenantId);

        // Full refresh je nameran: da se odmah primeni SetTenantDatabase middleware + TenantContext.
        $this->redirect(request()->header('Referer') ?? route('dashboard'), navigate: false);
    }

    public function render()
    {
        return view('livewire.layout.tenant-switcher');
    }
}
