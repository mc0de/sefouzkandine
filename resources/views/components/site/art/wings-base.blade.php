@props([
    'glaze' => '#C88A3C',
    'glazeShade' => '#8F5A22',
    'gloss' => '#F1C078',
    'garnish' => null,
    'garnishColor' => '#F6EBD2',
])

@php
    /**
     * Du sparneliai. Mėsa platesnė nei aukšta, viršus plokščias ir asimetriškas —
     * simetriškas apvalus kupolas ant kaulelio atrodo ne kaip mėsa.
     */
    $pieces = [
        ['t' => 'translate(64 104) rotate(-13)', 's' => 0.96],
        ['t' => 'translate(136 100) rotate(17)', 's' => 0.82],
    ];
@endphp

<svg {{ $attributes }} viewBox="0 0 200 176" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    @foreach ($pieces as $piece)
        <g transform="{{ $piece['t'] }} scale({{ $piece['s'] }})" stroke="#14110F" stroke-width="5.3" stroke-linecap="round" stroke-linejoin="round">
            {{--
                Kaulelis: pakrypęs į šoną, galas prasiplečia skersai — viena
                nesimetriška galvutė vietoj dviejų vienodų rutuliukų.
                Pirma piešiamas visas tamsus kontūras, ant jo — kreminis kaulas,
                kad sandūra liktų be perbraukiančių linijų.
            --}}
            <path d="M2 -6 18 26" stroke="#14110F" stroke-width="26" />
            <ellipse cx="22" cy="33" rx="16" ry="11" transform="rotate(-27 22 33)" fill="#14110F" stroke="#14110F" stroke-width="10" />
            <path d="M2 -6 18 26" stroke="#F6EBD2" stroke-width="15" />
            <ellipse cx="22" cy="33" rx="16" ry="11" transform="rotate(-27 22 33)" fill="#F6EBD2" stroke="none" />

            {{-- Glaistyta mėsa: plati, plokščiu viršumi, dešinysis petys stambesnis --}}
            <path
                d="M0 0C-16-2-30-14-34-30-38-47-29-63-13-68-1-72 15-71 26-65 38-58 42-43 38-29 34-13 18-2 0 0Z"
                fill="{{ $glaze }}"
            />

            <path d="M-25 -20q19 13 43-1" stroke="{{ $glazeShade }}" stroke-width="7" opacity=".4" />
            <path d="M-24 -48q12-13 27-7" stroke="{{ $gloss }}" stroke-width="8" opacity=".75" />

            @if ($garnish === 'sesame')
                <g stroke="none" fill="{{ $garnishColor }}">
                    <ellipse cx="-18" cy="-38" rx="5" ry="3" transform="rotate(-24 -18 -38)" />
                    <ellipse cx="2" cy="-52" rx="5" ry="3" transform="rotate(14 2 -52)" />
                    <ellipse cx="20" cy="-36" rx="5" ry="3" transform="rotate(-8 20 -36)" />
                    <ellipse cx="-2" cy="-20" rx="5" ry="3" transform="rotate(22 -2 -20)" />
                </g>
            @elseif ($garnish === 'chilli')
                <g stroke="none" fill="{{ $garnishColor }}">
                    <rect x="-24" y="-42" width="14" height="5.4" rx="2.7" transform="rotate(-28 -17 -39)" />
                    <rect x="-3" y="-56" width="14" height="5.4" rx="2.7" transform="rotate(18 4 -53)" />
                    <rect x="8" y="-32" width="14" height="5.4" rx="2.7" transform="rotate(-12 15 -29)" />
                </g>
            @elseif ($garnish === 'herb')
                <g stroke="{{ $garnishColor }}" stroke-width="5" fill="none">
                    <path d="M-21 -32 -12 -41" />
                    <path d="M-3 -54 8 -47" />
                    <path d="M6 -24 17 -32" />
                </g>
            @elseif ($garnish === 'pepper')
                <g stroke="none" fill="{{ $garnishColor }}">
                    <circle cx="-19" cy="-36" r="3.2" />
                    <circle cx="-1" cy="-54" r="3" />
                    <circle cx="20" cy="-33" r="3.4" />
                    <circle cx="0" cy="-18" r="2.8" />
                </g>
            @endif
        </g>
    @endforeach
</svg>
