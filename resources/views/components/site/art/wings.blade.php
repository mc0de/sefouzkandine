{{-- Dvi vištos kojelės, sukryžiuoti kauliukai --}}
<svg {{ $attributes }} viewBox="0 0 200 176" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    @foreach ([['tx' => 10, 'sx' => 0.85], ['tx' => 190, 'sx' => -0.85]] as $leg)
        <g transform="translate({{ $leg['tx'] }} 24) scale({{ $leg['sx'] }} 0.85)" stroke="#14110F" stroke-width="5.3" stroke-linecap="round" stroke-linejoin="round">
            {{-- Kauliukas: tamsus kontūras, ant jo kreminis kaulas --}}
            <path d="M92 82 136 128" stroke="#14110F" stroke-width="30" />
            <path d="M92 82 136 128" stroke="#F6EBD2" stroke-width="18" />
            <circle cx="142" cy="122" r="13" fill="#F6EBD2" />
            <circle cx="130" cy="135" r="13" fill="#F6EBD2" />
            <ellipse cx="64" cy="54" rx="42" ry="33" transform="rotate(-20 64 54)" fill="#C88A3C" />
            <g stroke="none" fill="#8F5A22" opacity=".5">
                <circle cx="44" cy="44" r="4.6" />
                <circle cx="70" cy="34" r="4.2" />
                <circle cx="82" cy="58" r="4.6" />
                <circle cx="52" cy="70" r="4.2" />
                <circle cx="28" cy="56" r="3.8" />
            </g>
            <path d="M32 34q14-12 32-9" stroke="#F1C078" stroke-width="8" opacity=".7" />
        </g>
    @endforeach
</svg>
