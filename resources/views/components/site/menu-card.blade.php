@props([
    'name',
    'description' => null,
    'price',
    'art' => null,
    'tag' => null,
    'delay' => 0,
])

@php
    $tagTones = [
        'hit' => 'bg-ink text-cream',
        'new' => 'bg-mustard text-ink',
        'spicy' => 'bg-flame text-cream',
        'vege' => 'bg-pickle text-cream',
    ];

    $artComponent = filled($art) && view()->exists('components.site.art.'.$art)
        ? 'site.art.'.$art
        : null;
@endphp

{{-- Siaura kortelė-eilutė: iliustracija kairėje, kaina dešinėje, taškinė linija tarp jų. --}}
<article class="sefo-card sefo-frame sefo-reveal flex items-center gap-4 bg-cream p-4 sm:gap-5 sm:p-5" style="--d: {{ $delay }}ms">
    <div class="relative flex size-20 shrink-0 items-center justify-center overflow-hidden border-[3px] border-ink bg-paper sm:size-24">
        <div class="sefo-halftone pointer-events-none absolute inset-0 text-ink"></div>

        @if ($artComponent)
            <x-dynamic-component :component="$artComponent" class="sefo-card__art relative h-[76%] w-auto" />
        @else
            <span class="sefo-display relative text-3xl text-ink/20">{{ mb_substr($name, 0, 1) }}</span>
        @endif
    </div>

    <div class="flex min-w-0 flex-1 flex-wrap items-center gap-x-3 gap-y-2">
        <h3 class="sefo-display min-w-0 text-xl break-words text-ink sm:text-2xl">{{ $name }}</h3>

        @if (isset($tagTones[$tag]))
            <span class="sefo-label px-2 py-1.5 text-xs {{ $tagTones[$tag] }}">
                {{ __('site.tags.'.$tag) }}
            </span>
        @endif

        <span class="hidden h-px flex-1 border-b-2 border-dotted border-ink/25 sm:block"></span>

        <p class="sefo-card__price sefo-stamp ml-auto shrink-0 text-lg leading-none sm:ml-0">{{ $price }} €</p>

        @if (filled($description))
            <p class="w-full text-sm leading-relaxed text-soot">{{ $description }}</p>
        @endif
    </div>
</article>
