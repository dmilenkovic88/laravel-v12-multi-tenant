<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Core\Tenancy\Models\Tenant;

new class extends Component
{
    public ?int $default_tenant_id = null;
    public array $tenants = [];
    public bool $editing = false;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->default_tenant_id = $user->default_tenant_id;

        $query = Tenant::query()->active()->orderBy('name');

        if ($user->type !== 'super_admin') {
            $query->whereHas('users', fn ($q) => $q->where('users.id', $user->id));
        }

        $this->tenants = $query
            ->get(['id', 'name'])
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
            ])
            ->toArray();
    }

    public function updatedDefaultTenantId($value): void
    {
        $user = Auth::user();
        if (! $user) return;

        $tenantId = $value ? (int) $value : null;

        // Validacija pristupa
        if ($tenantId) {
            $allowed = collect($this->tenants)
                ->pluck('id')
                ->contains($tenantId);

            if (! $allowed) {
                abort(403);
            }
        }

        $user->forceFill([
            'default_tenant_id' => $tenantId,
        ])->save();

        $this->editing = false;

        $this->dispatch('toast',
            type: 'success',
            message: 'Podrazumevani tenant je sačuvan.'
        );
    }

    public function edit(): void
    {
        $this->editing = true;
    }
};
?>

<div>
    <flux:label>Podrazumevani tenant</flux:label>

    @if($default_tenant_id && !$editing)

        @php
            $current = collect($tenants)->firstWhere('id', $default_tenant_id);
        @endphp

        <div class="flex items-center gap-3 mt-2">
            <flux:badge color="blue" size="lg">
                {{ $current['name'] ?? 'Nepoznat tenant' }}
            </flux:badge>

            <flux:button size="sm" variant="filled" icon="pencil-square" wire:click="edit">
                Promeni
            </flux:button>
        </div>

    @else

        <flux:select
            wire:model.live="default_tenant_id"
            {{-- placeholder="Izaberi tenant..." --}}
            class="mt-2"
        >
            <flux:select.option value="">
                Bez podrazumevanog Tenanta
            </flux:select.option>

            @foreach($tenants as $tenant)
                <flux:select.option value="{{ $tenant['id'] }}">
                    {{ $tenant['name'] }}
                </flux:select.option>
            @endforeach
        </flux:select>

    @endif

</div>
