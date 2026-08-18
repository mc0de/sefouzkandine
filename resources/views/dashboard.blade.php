@php
    /** Greitoji navigacija. Administravimo kortelės rodomos tik administratoriams. */
    $shortcuts = collect([
        [
            'title' => __('Users'),
            'text' => __('Add staff accounts, change roles or remove access.'),
            'icon' => 'users',
            'href' => Route::has('admin.users') ? route('admin.users') : null,
            'admin' => true,
        ],
        [
            'title' => __('Menu'),
            'text' => __('Edit categories, items and prices in both languages.'),
            'icon' => 'squares-2x2',
            'href' => Route::has('admin.menu') ? route('admin.menu') : null,
            'admin' => true,
        ],
        [
            'title' => __('Opening hours'),
            'text' => __('Set the window the kitchen is open on each weekday.'),
            'icon' => 'clock',
            'href' => Route::has('admin.hours') ? route('admin.hours') : null,
            'admin' => true,
        ],
        [
            'title' => __('Settings'),
            'text' => __('Update your profile, password and security options.'),
            'icon' => 'cog-6-tooth',
            'href' => route('profile.edit'),
            'admin' => false,
        ],
    ])->filter(fn (array $shortcut): bool => filled($shortcut['href'])
        && (! $shortcut['admin'] || Gate::allows('access-admin')));
@endphp

<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col">
        <flux:heading size="xl" level="1">{{ __('Dashboard') }}</flux:heading>
        <flux:subheading size="lg">{{ __('Jump straight to what you need') }}</flux:subheading>

        <flux:separator variant="subtle" class="mt-6" />

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($shortcuts as $shortcut)
                <a
                    href="{{ $shortcut['href'] }}"
                    wire:navigate
                    class="group flex flex-col items-start rounded-xl border border-neutral-200 p-6 transition hover:border-neutral-300 hover:bg-neutral-50 dark:border-neutral-700 dark:hover:border-neutral-600 dark:hover:bg-neutral-800"
                >
                    <span class="flex size-11 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 transition group-hover:bg-white dark:bg-white/10 dark:text-neutral-200 dark:group-hover:bg-white/20">
                        <flux:icon :icon="$shortcut['icon']" class="size-6" />
                    </span>

                    <flux:heading size="lg" class="mt-4">{{ $shortcut['title'] }}</flux:heading>
                    <flux:text class="mt-1">{{ $shortcut['text'] }}</flux:text>

                    <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-500 transition group-hover:text-neutral-900 dark:text-neutral-400 dark:group-hover:text-white">
                        {{ __('Open') }}
                        <flux:icon icon="arrow-right" class="size-4 transition group-hover:translate-x-0.5" />
                    </span>
                </a>
            @endforeach

            {{-- Vieša svetainė atsidaro naujame skirtuke: kitas maketas, be Livewire navigacijos. --}}
            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener"
                class="group flex flex-col items-start rounded-xl border border-dashed border-neutral-300 p-6 transition hover:border-neutral-400 hover:bg-neutral-50 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
            >
                <span class="flex size-11 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 transition group-hover:bg-white dark:bg-white/10 dark:text-neutral-200 dark:group-hover:bg-white/20">
                    <flux:icon icon="globe-alt" class="size-6" />
                </span>

                <flux:heading size="lg" class="mt-4">{{ __('View the storefront') }}</flux:heading>
                <flux:text class="mt-1">{{ __('See the public site exactly as customers do.') }}</flux:text>

                <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-500 transition group-hover:text-neutral-900 dark:text-neutral-400 dark:group-hover:text-white">
                    {{ __('Open in a new tab') }}
                    <flux:icon icon="arrow-top-right-on-square" class="size-4" />
                </span>
            </a>
        </div>
    </div>
</x-layouts::app>
