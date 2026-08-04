{{-- Traškūs vištienos strips su padažo kaušeliu --}}
<svg {{ $attributes }} viewBox="0 0 200 176" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <g stroke="#14110F" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round">
        @foreach ([['x' => 6, 'y' => 8, 'r' => -13], ['x' => 18, 'y' => 60, 'r' => -3], ['x' => 4, 'y' => 110, 'r' => 9]] as $strip)
            <g transform="translate({{ $strip['x'] }} {{ $strip['y'] }}) rotate({{ $strip['r'] }} 50 21)">
                <rect x="0" y="0" width="100" height="42" rx="20" fill="#C88A3C" />
                <g stroke="none" fill="#8F5A22" opacity=".5">
                    <circle cx="22" cy="14" r="3.4" />
                    <circle cx="48" cy="27" r="3.6" />
                    <circle cx="74" cy="15" r="3.2" />
                    <circle cx="36" cy="32" r="3" />
                    <circle cx="62" cy="34" r="2.8" />
                </g>
                <path d="M16 12q26-9 54 2" stroke="#F1C078" stroke-width="5.5" opacity=".65" />
            </g>
        @endforeach

        <path d="M130 96h66l-9 56a12 12 0 0 1-12 10h-24a12 12 0 0 1-12-10Z" fill="#FDF8EC" />
        <path d="M133 112h60l-3 16h-54Z" fill="#C4122B" stroke-width="0" />
    </g>
</svg>
