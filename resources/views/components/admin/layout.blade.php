{{-- Navigacija tarp administravimo puslapių gyvena šoninėje juostoje, todėl čia tik antraštė ir turinys. --}}
<div class="w-full">
    <flux:heading size="xl" level="1">{{ $heading ?? '' }}</flux:heading>
    <flux:subheading size="lg">{{ $subheading ?? '' }}</flux:subheading>

    <flux:separator variant="subtle" class="mt-6" />

    <div class="mt-6 w-full">
        {{ $slot }}
    </div>
</div>
