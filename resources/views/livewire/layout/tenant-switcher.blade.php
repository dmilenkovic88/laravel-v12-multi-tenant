<div class="min-w-[220px]">
    <flux:select
        wire:model.live="selectedTenantId"
        placeholder="Izaberi tenant..."
        size="sm"
    >
        <flux:select.option value="">Bez tenanta</flux:select.option>

        @foreach($tenants as $tenant)
            <flux:select.option value="{{ $tenant['id'] }}">
                {{ $tenant['name'] }}
            </flux:select.option>
        @endforeach
    </flux:select>
</div>
