@php
    /**
     * Vieša svetainė. Meniu ir darbo laikas ateina iš duomenų bazės, kontaktai —
     * iš config/site.php, visi statiniai tekstai — iš lang/{locale}/site.php.
     */
    $locales = config('site.locales', []);
    $defaultLocale = \App\Http\Middleware\SetLocale::default();
    $activeLocale = app()->getLocale();

    $localeUrl = fn (string $code): string => $code === $defaultLocale ? route('home') : route('home.'.$code);

    $phone = config('site.phone');
    $phoneHref = 'tel:'.str_replace(' ', '', (string) $phone);
    $address = config('site.address');
    // Kol el. paštas neveikia, adresas svetainėje nerodomas.
    // $email = config('site.email');
    $mapsUrl = config('site.maps');

    $metaDescription = __('site.meta.description', ['address' => $address, 'phone' => $phone]);

    $nav = [
        __('site.nav.menu') => '#meniu',
        __('site.nav.contacts') => '#kontaktai',
    ];

    /** Bėgantis skyrių numeratorius — 01, 02, 03… */
    $section = 0;
    $eyebrow = function (string $label) use (&$section): string {
        return str_pad((string) ++$section, 2, '0', STR_PAD_LEFT).' / '.$label;
    };

    /** „Pirmadienis–Sekmadienis“ arba viena diena, kai eilutė vienadienė. */
    $dayRange = fn (array $row): string => $row['from'] === $row['to']
        ? __('site.days.'.$row['from'])
        : __('site.days.'.$row['from']).'–'.__('site.days.'.$row['to']);

    /** Tos pačios žymų spalvos kaip x-site.menu-card. */
    $tagTones = [
        'hit' => 'bg-ink text-cream',
        'new' => 'bg-mustard text-ink',
        'spicy' => 'bg-flame text-cream',
        'vege' => 'bg-pickle text-cream',
    ];

    $schemaDays = [1 => 'Mo', 2 => 'Tu', 3 => 'We', 4 => 'Th', 5 => 'Fr', 6 => 'Sa', 7 => 'Su'];

    $schemaHours = [];

    foreach ($openingHours as $row) {
        if (blank($row['window'])) {
            continue;
        }

        $schemaHours[] = ($row['from'] === $row['to']
            ? $schemaDays[$row['from']]
            : $schemaDays[$row['from']].'-'.$schemaDays[$row['to']]
        ).' '.str_replace('–', '-', $row['window']);
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => __('site.brand'),
        'legalName' => config('site.company.name'),
        'sameAs' => array_values(array_filter(config('site.social', []))),
        'description' => $metaDescription,
        'url' => $localeUrl($activeLocale),
        'image' => asset('logo/sefo-logo-1200.webp'),
        'telephone' => $phone,
        // 'email' => $email,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
            'addressLocality' => 'Vilnius',
            'addressCountry' => 'LT',
        ],
        'openingHours' => $schemaHours,
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('site.meta.title') }}</title>
        <meta name="description" content="{{ $metaDescription }}">

        <link rel="canonical" href="{{ $localeUrl($activeLocale) }}">
        @foreach ($locales as $code => $settings)
            <link rel="alternate" hreflang="{{ $code }}" href="{{ $localeUrl($code) }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ $localeUrl($defaultLocale) }}">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ __('site.brand') }}">
        <meta property="og:title" content="{{ __('site.meta.title') }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $localeUrl($activeLocale) }}">
        <meta property="og:locale" content="{{ $activeLocale }}">
        <meta property="og:image" content="{{ asset('logo/sefo-logo-1200.webp') }}">
        <meta name="twitter:card" content="summary_large_image">

        <link rel="icon" href="{{ asset('logo/sefo-logo-256.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('logo/sefo-logo-256.png') }}">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/site.js'])

        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    </head>

    <body class="sefo-shell sefo-grain pb-[4.75rem] lg:pb-0">
        <a href="#meniu" class="sefo-label sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-[100] focus:bg-ink focus:px-4 focus:py-3 focus:text-cream">
            {{ __('site.skip_to_menu') }}
        </a>

        {{-- Antraštė --}}
        <header data-site-header class="sefo-header sticky top-0 z-50 border-b-[3px] border-ink bg-paper/95 backdrop-blur-sm">
            <div class="mx-auto flex max-w-[1400px] items-center gap-5 px-5 py-3 lg:gap-6 lg:px-10">
                <a href="{{ $localeUrl($activeLocale) }}" class="flex shrink-0 items-center" aria-label="{{ __('site.brand') }}">
                    <img
                        src="{{ asset('logo/sefo-logo-512.webp') }}"
                        alt="{{ __('site.meta.logo_alt') }}"
                        class="sefo-header__logo w-auto"
                        width="463"
                        height="512"
                    >
                </a>

                <nav class="ml-auto hidden items-center gap-8 lg:flex" aria-label="{{ __('site.nav.menu') }}">
                    @foreach ($nav as $label => $href)
                        <a href="{{ $href }}" class="sefo-navlink sefo-label text-sm text-ink">{{ $label }}</a>
                    @endforeach
                </nav>

                <div class="ml-auto flex items-center gap-3 lg:ml-0 lg:gap-4">
                    <x-site.social-links tone="light" size="sm" class="hidden xl:flex" />

                    <x-site.language-switcher />

                    <a href="{{ $phoneHref }}" class="hidden xl:block">
                        <span class="sefo-label block text-sm text-soot/70">{{ __('site.cta.phone_label') }}</span>
                        <span class="sefo-display mt-1.5 block text-lg text-ink">{{ $phone }}</span>
                    </a>

                    <a href="{{ $phoneHref }}" class="sefo-btn sefo-btn--flame hidden px-5 py-3 text-sm lg:inline-flex">
                        {{ __('site.cta.call') }}
                    </a>

                    <button
                        type="button"
                        data-nav-toggle
                        aria-expanded="false"
                        aria-label="{{ __('site.open_menu') }}"
                        class="sefo-frame-sm flex size-11 shrink-0 items-center justify-center bg-cream lg:hidden"
                    >
                        <span class="flex flex-col gap-[5px]">
                            <span class="block h-[3px] w-5 bg-ink"></span>
                            <span class="block h-[3px] w-5 bg-ink"></span>
                            <span class="block h-[3px] w-5 bg-ink"></span>
                        </span>
                    </button>
                </div>
            </div>

            <div data-nav-panel class="sefo-nav-panel border-t-[3px] border-ink bg-cream lg:hidden">
                <div>
                    <nav class="flex flex-col px-5 pb-6" aria-label="{{ __('site.nav.menu') }}">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="sefo-label border-b-2 border-dashed border-ink/20 py-4 text-sm text-ink">
                                {{ $label }}
                            </a>
                        @endforeach

                        <a href="{{ $phoneHref }}" class="sefo-btn sefo-btn--flame mt-6 w-full">
                            {{ __('site.cta.call') }}
                        </a>

                        <div class="mt-6 flex items-center justify-between gap-4">
                            <span class="sefo-label text-sm text-soot/70">{{ __('site.language.label') }}</span>
                            <x-site.language-switcher />
                        </div>

                        <div class="mt-6 flex items-center justify-between gap-4">
                            <span class="sefo-label text-sm text-soot/70">{{ __('site.footer.social') }}</span>
                            <x-site.social-links tone="light" size="sm" />
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            {{-- Herojus --}}
            <section class="relative overflow-hidden border-b-[3px] border-ink">
                <div class="sefo-grid-lines pointer-events-none absolute inset-0"></div>
                <div class="pointer-events-none absolute -top-40 -right-32 size-[520px] rounded-full bg-flame/[0.07]"></div>

                <div class="relative mx-auto grid max-w-[1400px] items-center gap-20 px-5 pt-14 pb-16 lg:grid-cols-12 lg:gap-10 lg:px-10 lg:pt-20 lg:pb-20">
                    <div class="lg:col-span-7">
                        <p class="sefo-rise sefo-label inline-block bg-ink px-4 py-3 text-sm text-cream" style="--d: 60ms">
                            {{ __('site.hero.eyebrow') }}
                        </p>

                        <h1 class="sefo-display mt-7 text-[clamp(3.1rem,9.4vw,7.4rem)] text-ink">
                            <span class="sr-only">{{ __('site.hero.slogan') }}</span>
                            @foreach (__('site.hero.words') as $word)
                                <span
                                    aria-hidden="true"
                                    @class([
                                        'sefo-rise block',
                                        'sefo-outline' => $loop->index === 1,
                                        'text-flame' => $loop->last,
                                    ])
                                    style="--d: {{ 160 + $loop->index * 100 }}ms"
                                >{{ $word }}{{ $loop->last ? '.' : ',' }}</span>
                            @endforeach
                        </h1>

                        <p class="sefo-rise mt-8 max-w-[46ch] text-lg leading-relaxed text-soot" style="--d: 460ms">
                            {{ __('site.hero.lead') }}
                        </p>

                        <div class="sefo-rise mt-10 flex flex-wrap items-center gap-4" style="--d: 540ms">
                            <a href="{{ $phoneHref }}" class="sefo-btn sefo-btn--flame">{{ __('site.cta.call') }}</a>
                            <a href="#meniu" class="sefo-btn sefo-btn--paper">{{ __('site.cta.see_menu') }}</a>
                        </div>

                        <dl class="sefo-rise mt-14 grid max-w-2xl gap-6 border-t-[3px] border-ink pt-6 sm:grid-cols-2" style="--d: 640ms">
                            <div>
                                <dt class="sefo-label text-sm text-soot/70">{{ __('site.cta.phone_label') }}</dt>
                                <dd class="mt-2.5">
                                    <a href="{{ $phoneHref }}" class="sefo-display text-[clamp(1.5rem,3.4vw,2.1rem)] text-flame transition-colors hover:text-ink">{{ $phone }}</a>
                                </dd>
                            </div>
                            <div class="sm:border-l-[3px] sm:border-dashed sm:border-ink/25 sm:pl-7">
                                <dt class="sefo-label text-sm text-soot/70">{{ __('site.contacts.address') }}</dt>
                                <dd class="mt-2.5">
                                    <a
                                        href="{{ $mapsUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="group inline-flex items-start gap-2.5 text-base leading-relaxed text-soot transition-colors hover:text-flame"
                                    >
                                        <svg class="mt-0.5 size-5 shrink-0 text-flame transition-transform group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>
                                        <span>{{ $address }}</span>
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative lg:col-span-5">
                        <div class="relative mx-auto aspect-square w-full max-w-[440px]">
                            <div class="sefo-ring absolute inset-0 rounded-full border-[3px] border-dashed border-ink/35"></div>
                            <div class="sefo-ring sefo-ring--slow absolute inset-6 rounded-full border-[3px] border-dotted border-flame/45"></div>
                            <div class="absolute inset-11 overflow-hidden rounded-full border-[3px] border-ink bg-cream">
                                <div class="sefo-halftone absolute inset-0 text-ink"></div>
                            </div>
                            <img
                                src="{{ asset('logo/sefo-logo-1200.webp') }}"
                                alt="{{ __('site.meta.logo_alt') }}"
                                class="sefo-rise absolute inset-0 m-auto w-[74%]"
                                style="--d: 240ms"
                                width="1084"
                                height="1200"
                                fetchpriority="high"
                            >
                        </div>

                        <div class="sefo-float pointer-events-none absolute -bottom-10 -left-2 w-32 sm:w-40 lg:-left-8">
                            <x-site.art.wings class="w-full" />
                        </div>

                        <div class="sefo-frame-sm sefo-rise absolute -top-3 right-3 rotate-[7deg] bg-mustard px-4 py-3 text-center lg:-right-4" style="--d: 700ms">
                            <p class="sefo-label text-xs text-ink/70">{{ __('site.hero.badge') }}</p>
                            <a href="{{ $phoneHref }}" class="sefo-display mt-1.5 block text-lg text-ink">{{ $phone }}</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Meniu --}}
            <section id="meniu" class="scroll-mt-24 border-b-[3px] border-ink bg-paper">
                <div class="mx-auto max-w-[1400px] px-5 py-14 lg:px-10 lg:py-20">
                    @forelse ($categories as $category)
                        <div @class([
                            'sefo-reveal flex flex-wrap items-end justify-between gap-x-8 gap-y-5 border-b-[3px] border-ink pb-5',
                            'mt-14' => ! $loop->first,
                        ])>
                            <div>
                                <p class="sefo-label text-sm text-flame">{{ $eyebrow(__('site.menu.eyebrow')) }}</p>
                                <h2 class="sefo-display mt-3 text-[clamp(2rem,4.6vw,3.4rem)] text-ink">{{ $category->name }}</h2>
                            </div>

                            @if (filled($category->description))
                                <p class="sefo-frame-sm max-w-[46ch] bg-mustard px-5 py-4 text-base leading-snug font-semibold text-ink lg:text-lg">
                                    {{ $category->description }}
                                </p>
                            @endif
                        </div>

                        @if ($category->layout === 'list')
                            {{-- Kainoraštis: viena stambi iliustracija kategorijai ir eilutės su kainomis. --}}
                            <div class="sefo-reveal sefo-frame mt-8 bg-cream">
                                <div class="grid gap-8 p-6 lg:grid-cols-12 lg:p-8">
                                    <div class="flex items-center lg:col-span-3">
                                        @if (filled($category->items->first()?->art))
                                            <div class="relative flex size-32 shrink-0 items-center justify-center overflow-hidden border-[3px] border-ink bg-paper lg:size-40">
                                                <div class="sefo-halftone pointer-events-none absolute inset-0 text-ink"></div>
                                                <x-dynamic-component :component="'site.art.'.$category->items->first()->art" class="relative h-[76%] w-auto" />
                                            </div>
                                        @endif
                                    </div>

                                    <ul class="lg:col-span-9">
                                        @foreach ($category->items as $item)
                                            <li class="py-3">
                                                <div class="flex items-baseline gap-4">
                                                    <h3 class="sefo-display text-xl text-ink sm:text-2xl">{{ $item->name }}</h3>

                                                    @if (isset($tagTones[$item->tag]))
                                                        <span class="sefo-label px-2 py-1.5 text-xs {{ $tagTones[$item->tag] }}">
                                                            {{ __('site.tags.'.$item->tag) }}
                                                        </span>
                                                    @endif

                                                    <span class="h-px flex-1 border-b-2 border-dotted border-ink/25"></span>

                                                    <p class="sefo-stamp shrink-0 text-lg leading-none">{{ $item->formatted_price }} €</p>
                                                </div>

                                                @if (filled($item->description))
                                                    <p class="mt-2 max-w-[42ch] text-sm leading-relaxed text-soot">{{ $item->description }}</p>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="mt-8 grid gap-6 md:grid-cols-2">
                                @foreach ($category->items as $item)
                                    <x-site.menu-card
                                        :name="$item->name"
                                        :description="$item->description"
                                        :price="$item->formatted_price"
                                        :art="$item->art"
                                        :tag="$item->tag"
                                        :delay="$loop->index * 90"
                                    />
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <p class="sefo-display text-[clamp(1.6rem,4vw,2.4rem)] text-ink/50">{{ __('site.menu.empty') }}</p>
                    @endforelse
                </div>
            </section>

            {{-- Kontaktai --}}
            <section id="kontaktai" class="scroll-mt-24 border-b-[3px] border-ink bg-cream">
                <div class="mx-auto grid max-w-[1400px] gap-14 px-5 py-20 lg:grid-cols-12 lg:px-10 lg:py-28">
                    <div class="sefo-reveal lg:col-span-5">
                        <p class="sefo-label text-sm text-flame">{{ $eyebrow(__('site.contacts.eyebrow')) }}</p>
                        <h2 class="sefo-display mt-4 text-[clamp(2.4rem,5.6vw,4.2rem)] text-ink">{{ __('site.contacts.title') }}</h2>

                        <div class="mt-10 space-y-8">
                            <div>
                                <p class="sefo-label text-sm text-soot/70">{{ __('site.contacts.address') }}</p>
                                <p class="sefo-display mt-2 text-2xl text-ink">{{ $address }}</p>
                                <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="sefo-label mt-3 inline-block border-b-[3px] border-flame pb-1 text-sm text-ink transition-colors hover:text-flame">
                                    {{ __('site.contacts.directions') }} →
                                </a>
                            </div>
                            <div>
                                <p class="sefo-label text-sm text-soot/70">{{ __('site.contacts.phone') }}</p>
                                <a href="{{ $phoneHref }}" class="sefo-display mt-2 block text-2xl text-flame transition-colors hover:text-ink">
                                    {{ $phone }}
                                </a>
                            </div>
                            {{-- Įjungti, kai turėsime veikiančią el. pašto dėžutę
                            <div>
                                <p class="sefo-label text-sm text-soot/70">{{ __('site.contacts.email') }}</p>
                                <a href="mailto:{{ $email }}" class="sefo-display mt-2 block text-2xl text-ink transition-colors hover:text-flame">
                                    {{ $email }}
                                </a>
                            </div>
                            --}}
                        </div>
                    </div>

                    <div class="sefo-reveal lg:col-span-7" style="--d: 120ms">
                        <div class="sefo-frame bg-paper">
                            <div class="border-b-[3px] border-ink bg-ink px-8 py-5">
                                <p class="sefo-label text-sm text-cream">{{ __('site.contacts.hours') }}</p>
                            </div>

                            <dl class="px-8 py-5">
                                @foreach ($openingHours as $row)
                                    <div class="flex flex-wrap items-baseline gap-3 py-3">
                                        <dt class="sefo-label text-sm text-soot">{{ $dayRange($row) }}</dt>
                                        <span class="h-px flex-1 border-b-2 border-dotted border-ink/15"></span>
                                        <dd @class([
                                            'sefo-display text-lg',
                                            'text-ink' => filled($row['window']),
                                            'text-soot/50' => blank($row['window']),
                                        ])>{{ $row['window'] ?? __('site.contacts.closed') }}</dd>
                                    </div>
                                @endforeach
                            </dl>

                            <div class="relative overflow-hidden border-t-[3px] border-ink">
                                <div class="sefo-grid-lines absolute inset-0"></div>
                                <div class="relative flex items-center gap-5 px-8 py-8">
                                    <span class="relative flex size-12 shrink-0 items-center justify-center rounded-full border-[3px] border-ink bg-flame">
                                        <span class="size-3 rounded-full bg-cream"></span>
                                        <span class="absolute inset-0 animate-ping rounded-full border-[3px] border-flame/50"></span>
                                    </span>
                                    <p class="max-w-[34ch] text-base leading-relaxed text-soot">{{ __('site.contacts.takeaway') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Užsakymas --}}
            <section class="relative overflow-hidden border-b-[3px] border-ink bg-mustard">
                <div class="sefo-halftone pointer-events-none absolute inset-0 text-ink"></div>

                <div class="relative mx-auto flex max-w-[1400px] flex-col items-center gap-8 px-5 py-20 text-center lg:px-10 lg:py-24">
                    <p class="sefo-reveal sefo-label text-sm text-ink/70">{{ __('site.closing.eyebrow') }}</p>
                    <h2 class="sefo-reveal sefo-display max-w-[24ch] text-[clamp(2.6rem,7vw,5.4rem)] text-ink" style="--d: 80ms">
                        {{ __('site.closing.title') }}
                    </h2>
                    <a href="{{ $phoneHref }}" class="sefo-reveal sefo-display text-[clamp(1.9rem,5.4vw,3.6rem)] text-flame underline decoration-[6px] underline-offset-[10px] transition-colors hover:text-ink" style="--d: 160ms">
                        {{ $phone }}
                    </a>
                    <a href="{{ $phoneHref }}" class="sefo-reveal sefo-btn sefo-btn--ink" style="--d: 220ms">{{ __('site.cta.call') }}</a>
                    <p class="sefo-reveal max-w-[46ch] text-base leading-relaxed text-ink/70" style="--d: 280ms">
                        {{ __('site.closing.lead') }}
                    </p>
                </div>
            </section>
        </main>

        {{-- Poraštė --}}
        <footer class="bg-ink text-cream">
            <div class="mx-auto grid max-w-[1400px] gap-12 px-5 py-16 lg:grid-cols-12 lg:px-10 lg:py-20">
                <div class="lg:col-span-5">
                    <img
                        src="{{ asset('logo/sefo-logo-512.webp') }}"
                        alt="{{ __('site.meta.logo_alt') }}"
                        class="h-28 w-auto"
                        width="463"
                        height="512"
                        loading="lazy"
                    >
                    <p class="sefo-display mt-6 text-2xl text-mustard">{{ __('site.hero.slogan') }}</p>
                    <p class="mt-4 max-w-[34ch] text-base leading-relaxed text-cream/60">
                        {{ __('site.footer.about') }}
                    </p>

                    <p class="sefo-label mt-8 text-sm text-mustard">{{ __('site.footer.social') }}</p>
                    <x-site.social-links class="mt-4" />
                </div>

                <div class="lg:col-span-3">
                    <p class="sefo-label text-sm text-mustard">{{ __('site.footer.menu') }}</p>
                    <ul class="mt-5 space-y-3">
                        @foreach ($nav as $label => $href)
                            <li>
                                <a href="{{ $href }}" class="sefo-navlink text-base text-cream/80 hover:text-cream">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        @foreach ($locales as $code => $settings)
                            <a
                                href="{{ $localeUrl($code) }}"
                                hreflang="{{ $code }}"
                                lang="{{ $code }}"
                                @class([
                                    'sefo-label border-2 px-3 py-2 text-xs transition-colors',
                                    'border-mustard text-mustard' => $code === $activeLocale,
                                    'border-cream/25 text-cream/70 hover:text-cream' => $code !== $activeLocale,
                                ])
                            >{{ $settings['label'] }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <p class="sefo-label text-sm text-mustard">{{ __('site.footer.contacts') }}</p>
                    <ul class="mt-5 space-y-3 text-base text-cream/80">
                        <li>
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-cream">{{ $address }}</a>
                        </li>
                        <li><a href="{{ $phoneHref }}" class="hover:text-cream">{{ $phone }}</a></li>
                        {{-- <li><a href="mailto:{{ $email }}" class="hover:text-cream">{{ $email }}</a></li> --}}
                    </ul>

                    <a href="{{ $phoneHref }}" class="sefo-btn sefo-btn--flame mt-8">{{ __('site.cta.call') }}</a>
                </div>
            </div>

            <div class="mx-auto flex max-w-[1400px] flex-wrap items-center justify-between gap-4 border-t-[3px] border-cream/15 px-5 py-8 lg:px-10">
                <div class="sefo-label space-y-2 text-xs text-cream/50">
                    <p>{{ __('site.footer.rights', ['year' => now()->year]) }}</p>
                    <p>
                        {{ config('site.company.name') }}
                        <span class="px-1.5 text-cream/30">·</span>
                        {{ __('site.footer.company_code') }}: {{ config('site.company.code') }}
                    </p>
                </div>

                {{-- Prisijungimo nuoroda viešai nerodoma; prisijungusiems lieka nuoroda į valdymą. --}}
                @auth
                    <div class="sefo-label flex items-center gap-6 text-xs text-cream/50">
                        <a href="{{ route('dashboard') }}" class="hover:text-cream">{{ __('site.footer.dashboard') }}</a>
                    </div>
                @endauth
            </div>
        </footer>

        {{-- Skambučio juosta telefonuose: išsinešamo maisto užsakymai keliauja per telefoną --}}
        <a
            href="{{ $phoneHref }}"
            class="fixed inset-x-0 bottom-0 z-[95] flex items-center justify-center gap-3 border-t-[3px] border-ink bg-flame px-5 py-4 text-cream transition-colors hover:bg-ember lg:hidden"
        >
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6.5 3h3l1.5 4.5-2 1.5a12 12 0 0 0 6 6l1.5-2 4.5 1.5v3a2 2 0 0 1-2.2 2A17.5 17.5 0 0 1 4.5 5.2 2 2 0 0 1 6.5 3Z" />
            </svg>
            <span class="sefo-label text-sm">{{ __('site.cta.call') }}</span>
            <span class="sefo-display text-base">{{ $phone }}</span>
        </a>
    </body>
</html>
