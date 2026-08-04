@props([
    'items' => [],
    'tone' => 'flame',
    'reverse' => false,
])

@php
    $tones = [
        'flame' => 'bg-flame text-cream',
        'ink' => 'bg-ink text-cream',
        'mustard' => 'bg-mustard text-ink',
        'cream' => 'bg-cream text-ink',
    ];
@endphp

<div {{ $attributes->class(['relative overflow-hidden select-none', $tones[$tone] ?? $tones['flame']]) }}>
    <div @class(['sefo-marquee-track py-3', 'sefo-marquee-track--reverse' => $reverse])>
        @foreach (range(1, 2) as $pass)
            <div class="flex shrink-0 items-center" aria-hidden="{{ $pass === 2 ? 'true' : 'false' }}">
                @foreach ($items as $item)
                    <span class="sefo-label flex shrink-0 items-center gap-6 px-6 text-[0.9rem]">
                        {{ $item }}
                        <span class="text-[0.65rem] opacity-70">◆</span>
                    </span>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
