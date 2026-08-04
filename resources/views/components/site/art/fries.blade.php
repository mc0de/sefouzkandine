{{-- Gruzdintos bulvytės raudonoje pakuotėje --}}
<svg {{ $attributes }} viewBox="0 0 160 190" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <g stroke="#14110F" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round">
        @foreach ([['x' => 30, 'y' => 26, 'r' => -16], ['x' => 56, 'y' => 12, 'r' => -4], ['x' => 82, 'y' => 20, 'r' => 12], ['x' => 44, 'y' => 40, 'r' => 4], ['x' => 70, 'y' => 44, 'r' => -10]] as $fry)
            <rect x="{{ $fry['x'] }}" y="{{ $fry['y'] }}" width="18" height="90" rx="6" fill="#F1BE4C"
                transform="rotate({{ $fry['r'] }} {{ $fry['x'] + 9 }} {{ $fry['y'] + 45 }})" />
        @endforeach
        <path d="M22 96h116l-14 78a12 12 0 0 1-12 10H48a12 12 0 0 1-12-10Z" fill="#C4122B" />
        <path d="M28 124h104l-4 22H32Z" fill="#FDF8EC" stroke-width="0" opacity=".92" />
    </g>
</svg>
