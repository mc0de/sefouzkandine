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

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        <x-slot:icon>
                            <flux:icon icon="home" variant="outline" class="size-4 text-sky-500 dark:text-sky-400" />
                        </x-slot:icon>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @can('access-admin')
                    <flux:sidebar.group :heading="__('Admin')" class="grid">
                        <flux:sidebar.item :href="route('admin.users')" :current="request()->routeIs('admin.users*')" wire:navigate>
                            <x-slot:icon>
                                <flux:icon icon="users" variant="outline" class="size-4 text-blue-500 dark:text-blue-400" />
                            </x-slot:icon>
                            {{ __('Users') }}
                        </flux:sidebar.item>

                        <flux:sidebar.item :href="route('admin.menu')" :current="request()->routeIs('admin.menu*')" wire:navigate>
                            <x-slot:icon>
                                <flux:icon icon="squares-2x2" variant="outline" class="size-4 text-amber-500 dark:text-amber-400" />
                            </x-slot:icon>
                            {{ __('Menu') }}
                        </flux:sidebar.item>

                        <flux:sidebar.item :href="route('admin.hours')" :current="request()->routeIs('admin.hours*')" wire:navigate>
                            <x-slot:icon>
                                <flux:icon icon="clock" variant="outline" class="size-4 text-emerald-500 dark:text-emerald-400" />
                            </x-slot:icon>
                            {{ __('Opening hours') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endcan
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
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

                    @can('access-admin')
                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('admin.users')" wire:navigate>
                                <x-slot:icon>
                                    <flux:icon icon="users" variant="mini" class="size-4 text-blue-500 dark:text-blue-400" />
                                </x-slot:icon>
                                {{ __('Users') }}
                            </flux:menu.item>

                            <flux:menu.item :href="route('admin.menu')" wire:navigate>
                                <x-slot:icon>
                                    <flux:icon icon="squares-2x2" variant="mini" class="size-4 text-amber-500 dark:text-amber-400" />
                                </x-slot:icon>
                                {{ __('Menu') }}
                            </flux:menu.item>

                            <flux:menu.item :href="route('admin.hours')" wire:navigate>
                                <x-slot:icon>
                                    <flux:icon icon="clock" variant="mini" class="size-4 text-emerald-500 dark:text-emerald-400" />
                                </x-slot:icon>
                                {{ __('Opening hours') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />
                    @endcan

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" wire:navigate>
                            <x-slot:icon>
                                <flux:icon icon="cog-6-tooth" variant="mini" class="size-4 text-violet-500 dark:text-violet-400" />
                            </x-slot:icon>
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
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
