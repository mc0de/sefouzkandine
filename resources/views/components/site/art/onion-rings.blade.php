{{-- Traškūs svogūnų žiedai — bokštelis ir vienas atremtas žiedas --}}
<svg {{ $attributes }} viewBox="0 0 200 176" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <g stroke="#14110F" stroke-width="5.3" stroke-linecap="round" stroke-linejoin="round">
        {{-- Į bokštelį atremtas žiedas, piešiamas pirmas, kad liktų už kitų --}}
        <g transform="rotate(-14 154 112)">
            <circle cx="154" cy="112" r="35" fill="#C88A3C" />
            <circle cx="154" cy="112" r="14" fill="#F6EBD2" />
            <path d="M132 90q20-10 42-1" stroke="#F1C078" stroke-width="6" opacity=".65" />
            <g stroke="none" fill="#8F5A22" opacity=".5">
                <circle cx="136" cy="118" r="3.6" />
                <circle cx="152" cy="141" r="3.4" />
                <circle cx="174" cy="122" r="3.8" />
                <circle cx="170" cy="96" r="3.2" />
            </g>
        </g>

        {{-- Trys plokšti žiedai, sukrauti vienas ant kito --}}
        @foreach ([['cy' => 136, 'r' => -3], ['cy' => 106, 'r' => 5], ['cy' => 76, 'r' => -4]] as $ring)
            <g transform="rotate({{ $ring['r'] }} 96 {{ $ring['cy'] }})">
                <ellipse cx="96" cy="{{ $ring['cy'] }}" rx="56" ry="26" fill="#C88A3C" />
                <ellipse cx="96" cy="{{ $ring['cy'] }}" rx="22" ry="9" fill="#F6EBD2" />
                <path d="M56 {{ $ring['cy'] - 6 }}q16-14 40-12" stroke="#F1C078" stroke-width="6" opacity=".7" />
                <g stroke="none" fill="#8F5A22" opacity=".5">
                    <circle cx="66" cy="{{ $ring['cy'] + 10 }}" r="3.6" />
                    <circle cx="96" cy="{{ $ring['cy'] + 17 }}" r="3.4" />
                    <circle cx="126" cy="{{ $ring['cy'] + 9 }}" r="3.8" />
                    <circle cx="132" cy="{{ $ring['cy'] - 8 }}" r="3.2" />
                </g>
            </g>
        @endforeach
    </g>
</svg>
