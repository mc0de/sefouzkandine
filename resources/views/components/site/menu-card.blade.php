@props([
    'name',
    'description' => '',
    'price',
    'art' => 'burger',
    'weight' => null,
    'tag' => null,
    'delay' => 0,
])

@php
    $tagTones = [
        'HITAS' => 'bg-ink text-cream',
        'NAUJAS' => 'bg-mustard text-ink',
        'AŠTRU' => 'bg-flame text-cream',
        'VEGE' => 'bg-pickle text-cream',
    ];
@endphp

<article class="sefo-card sefo-frame sefo-reveal flex flex-col" style="--d: {{ $delay }}ms">
    <div class="relative overflow-hidden border-b-[3px] border-ink bg-paper px-6 pt-7 pb-5">
        <div class="sefo-halftone pointer-events-none absolute inset-0 text-ink"></div>

        @if ($tag)
            <span class="sefo-label absolute top-4 left-0 z-10 py-1.5 pr-3 pl-4 text-[0.68rem] {{ $tagTones[$tag] ?? $tagTones['HITAS'] }}">
                {{ $tag }}
            </span>
        @endif

        {{-- Fiksuotas aukštis, kad skirtingų proporcijų iliustracijos liktų vienoje linijoje --}}
        <div class="sefo-card__art relative flex h-44 items-end justify-center">
            <x-dynamic-component :component="'site.art.' . $art" class="h-full w-auto" />
        </div>
    </div>

    <div class="flex flex-1 flex-col bg-cream p-6">
        <h3 class="sefo-display min-h-[2em] text-[1.55rem] text-ink">{{ $name }}</h3>

        @if ($description)
            <p class="mt-3 text-[0.9rem] leading-relaxed text-soot">{{ $description }}</p>
        @endif

        <div class="sefo-dash mt-auto flex items-end justify-between gap-4 pt-5">
            <div>
                @if ($weight)
                    <p class="sefo-label text-[0.65rem] text-soot/70">{{ $weight }}</p>
                @endif
                <p class="sefo-card__price sefo-stamp mt-2 text-[1.15rem] leading-none">{{ $price }} €</p>
            </div>
            <a href="#uzsakymas" class="sefo-label shrink-0 border-b-[3px] border-flame pb-1 text-[0.7rem] text-ink transition-colors hover:text-flame">
                Į krepšelį →
            </a>
        </div>
    </div>
</article>
