{{-- LT / EN perjungiklis. Kalbos ir jų prefiksai aprašyti config/site.php --}}
@php
    $locales = config('site.locales', []);
    $defaultLocale = \App\Http\Middleware\SetLocale::default();
    $activeLocale = app()->getLocale();
@endphp

<div {{ $attributes->class('inline-flex items-stretch border-2 border-ink bg-cream') }} role="group" aria-label="{{ __('site.language.switch') }}">
    @foreach ($locales as $code => $settings)
        <a
            href="{{ $code === $defaultLocale ? route('home') : route('home.'.$code) }}"
            hreflang="{{ $code }}"
            lang="{{ $code }}"
            title="{{ $settings['label'] }}"
            @if ($code === $activeLocale) aria-current="true" @endif
            @class([
                'sefo-label px-2.5 py-2.5 text-sm transition-colors',
                'border-l-2 border-ink' => ! $loop->first,
                'bg-ink text-cream' => $code === $activeLocale,
                'text-ink hover:bg-mustard' => $code !== $activeLocale,
            ])
        >{{ $settings['short'] }}</a>
    @endforeach
</div>
