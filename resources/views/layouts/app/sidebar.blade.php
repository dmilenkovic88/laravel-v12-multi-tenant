<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            @auth
                <livewire:layout.tenant-switcher />
            @endauth

            <flux:separator variant="subtle" />

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('NuvioERP v1.0')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    @module('accounts')
                        <flux:sidebar.item icon="users" :href="route('ta.accounts.index')" :current="request()->routeIs('accounts.*')" wire:navigate>
                            {{ __('Accounts') }}
                        </flux:sidebar.item>
                    @endmodule
                    @module('contacts')
                        <flux:sidebar.item icon="users" :href="route('ta.contacts.index')" :current="request()->routeIs('contacts.*')" wire:navigate>
                            {{ __('Contacts') }}
                        </flux:sidebar.item>
                    @endmodule
                    @module('employees')
                        <flux:sidebar.item icon="users" :href="route('ta.employees.index')" :current="request()->routeIs('employees.*')" wire:navigate>
                            {{ __('Employees') }}
                        </flux:sidebar.item>
                    @endmodule
                    @module('projects')
                        <flux:sidebar.item icon="folder-open" :href="route('ta.projects.index')" :current="request()->routeIs('projects.*')" wire:navigate>
                            {{ __('Projects') }}
                        </flux:sidebar.item>
                    @endmodule
                    @module('tasks')
                        <flux:sidebar.item icon="check-circle" :href="route('ta.tasks.index')" :current="request()->routeIs('tasks.*')" wire:navigate>
                            {{ __('Tasks') }}
                        </flux:sidebar.item>
                    @endmodule
                </flux:sidebar.group>
            </flux:sidebar.nav>




            <flux:spacer />

            {{-- <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav> --}}

            @php
                $ctx = app(\App\Core\Context\TenantContext::class)->tenant();
                $database = \Illuminate\Support\Facades\DB::connection('tenant')->getDatabaseName();
            @endphp

            <flux:badge color="zinc" size="sm">
                {{ $ctx->name ?? 'N/A' }} <br>
                {{ $database }}
            </flux:badge>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->username" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
