<section class="w-full">
    <x-admin.layout :heading="__('Opening hours')" :subheading="__('Set the window the kitchen is open on each weekday')">
        <form wire:submit="save" class="mt-2 space-y-6">
            <div class="space-y-4">
                @foreach ($days as $index => $day)
                    <div wire:key="day-{{ $day['day_of_week'] }}" class="grid items-start gap-4 rounded-xl border border-zinc-200 p-4 md:grid-cols-4 dark:border-zinc-700">
                        <div class="self-center">
                            <flux:heading>{{ $this->dayLabel($day['day_of_week']) }}</flux:heading>
                        </div>

                        <div class="self-center">
                            <flux:switch wire:model.live="days.{{ $index }}.is_closed" :label="__('Closed')" />
                        </div>

                        <flux:input
                            wire:model="days.{{ $index }}.opens_at"
                            :label="__('Opens at')"
                            type="time"
                            :disabled="$day['is_closed']"
                        />

                        <flux:input
                            wire:model="days.{{ $index }}.closes_at"
                            :label="__('Closes at')"
                            type="time"
                            :disabled="$day['is_closed']"
                        />
                    </div>
                @endforeach
            </div>

            @if ($errors->any())
                <flux:callout variant="danger" icon="exclamation-triangle" :heading="__('Please fix the highlighted opening hours.')">
                    <flux:callout.text>
                        <ul class="list-disc ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </flux:callout.text>
                </flux:callout>
            @endif

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-admin.layout>
</section>
