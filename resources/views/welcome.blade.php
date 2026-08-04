@php
    $burgers = config('menu.burgers');
    $chicken = config('menu.chicken');
    $sides = config('menu.sides');
    $reviews = config('menu.reviews');
    $hours = config('menu.hours');
    $phone = config('menu.phone');
    $phoneHref = 'tel:'.str_replace(' ', '', $phone);
    $address = config('menu.address');
    $email = config('menu.email');

    $nav = [
        'Meniu' => '#meniu',
        'Rinkiniai' => '#rinkiniai',
        'Kaip veikia' => '#kaip-veikia',
        'Atsiliepimai' => '#atsiliepimai',
        'Kontaktai' => '#kontaktai',
    ];

    $stats = [
        ['value' => '12', 'unit' => 'min', 'label' => 'Vidutinis laukimas'],
        ['value' => '200', 'unit' => 'g', 'label' => 'Rankų darbo mėsa'],
        ['value' => '4,9', 'unit' => '★', 'label' => '1 240 atsiliepimų'],
    ];

    $steps = [
        [
            'no' => '01',
            'title' => 'Pasirink',
            'text' => 'Rinkis prie kasos, telefonu arba pristatymo programėlėje. Meniu trumpas, nes jame tik tai, ką darome geriausiai.',
        ],
        [
            'no' => '02',
            'title' => 'Kepame',
            'text' => 'Mėsa prispaudžiama ant įkaitintos plytos, vištiena leidžiama į gruzdintuvę. Tik tada, kai užsisakai.',
        ],
        [
            'no' => '03',
            'title' => 'Atsiimk',
            'text' => 'Vidutiniškai per 12 minučių ant prekystalio. Pristatymas Kaune — iki 35 minučių.',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="lt" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Šefo Užkandinė — burgeriai ir vištiena ant atviros ugnies</title>
        <meta name="description" content="Rankomis formuoti burgeriai ir per naktį marinuota vištiena Kaune. Greita ir skanu — vidutiniškai per 12 minučių.">

        <link rel="icon" href="{{ asset('logo/sefo-logo-256.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('logo/sefo-logo-256.png') }}">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/site.js'])
    </head>

    <body class="sefo-shell sefo-grain">
        <a href="#meniu" class="sefo-label sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-[100] focus:bg-ink focus:px-4 focus:py-3 focus:text-cream">
            Pereiti prie meniu
        </a>

        {{-- Viršutinė bėganti juosta --}}
        <x-site.marquee
            tone="flame"
            class="border-b-[3px] border-ink"
            :items="['Greita ir skanu', 'Kepame nuo 11:00', 'Pristatymas visame Kaune', 'Rankų darbo mėsa', 'Nuo 2014 metų', 'Vištiena marinuojama 24 h']"
        />

        {{-- Antraštė --}}
        <header data-site-header class="sefo-header sticky top-0 z-50 border-b-[3px] border-ink bg-paper/95 backdrop-blur-sm">
            <div class="mx-auto flex max-w-[1400px] items-center gap-6 px-5 py-3 lg:px-10">
                <a href="#" class="flex shrink-0 items-center" aria-label="Šefo Užkandinė">
                    <img
                        src="{{ asset('logo/sefo-logo-512.webp') }}"
                        alt="Šefo Užkandinė"
                        class="sefo-header__logo w-auto"
                        width="463"
                        height="512"
                    >
                </a>

                <nav class="ml-auto hidden items-center gap-8 lg:flex">
                    @foreach ($nav as $label => $href)
                        <a href="{{ $href }}" class="sefo-navlink sefo-label text-[0.72rem] text-ink">{{ $label }}</a>
                    @endforeach
                </nav>

                <div class="ml-auto flex items-center gap-4 lg:ml-0">
                    <a href="{{ $phoneHref }}" class="hidden xl:block">
                        <span class="sefo-label block text-[0.58rem] text-soot/70">Užsisakyk telefonu</span>
                        <span class="sefo-display mt-1.5 block text-[1.05rem] text-ink">{{ $phone }}</span>
                    </a>

                    <a href="#uzsakymas" class="sefo-btn sefo-btn--flame hidden px-5 py-3 text-[0.78rem] sm:inline-flex">
                        Užsisakyti
                    </a>

                    <button
                        type="button"
                        data-nav-toggle
                        aria-expanded="false"
                        aria-label="Atidaryti meniu"
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
                    <nav class="flex flex-col px-5 pb-5">
                        @foreach ($nav as $label => $href)
                            <a href="{{ $href }}" class="sefo-label border-b-2 border-dashed border-ink/20 py-4 text-[0.78rem] text-ink">
                                {{ $label }}
                            </a>
                        @endforeach
                        <a href="{{ $phoneHref }}" class="sefo-display mt-5 text-[1.3rem] text-flame">{{ $phone }}</a>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            {{-- Herojus --}}
            <section class="relative overflow-hidden border-b-[3px] border-ink">
                <div class="sefo-grid-lines pointer-events-none absolute inset-0"></div>
                <div class="pointer-events-none absolute -top-40 -right-32 size-[520px] rounded-full bg-flame/[0.07]"></div>
                <div class="sefo-halftone pointer-events-none absolute -bottom-12 left-1/2 h-56 w-[72%] -translate-x-1/2 text-flame"></div>

                <div class="relative mx-auto grid max-w-[1400px] items-center gap-20 px-5 pt-14 pb-24 lg:grid-cols-12 lg:gap-10 lg:px-10 lg:pt-20 lg:pb-28">
                    <div class="lg:col-span-7">
                        <p class="sefo-rise sefo-label inline-block -rotate-[1.8deg] bg-ink px-4 py-2.5 text-[0.64rem] text-cream" style="--d: 60ms">
                            Kaunas · Greita ir skanu nuo 2014
                        </p>

                        <h1 class="sefo-display mt-7 text-[clamp(3.1rem,9.4vw,7.4rem)] text-ink">
                            <span class="sefo-rise block" style="--d: 160ms">Ugnis.</span>
                            <span class="sefo-rise sefo-outline block" style="--d: 260ms">Mėsa.</span>
                            <span class="sefo-rise block text-flame" style="--d: 360ms">Traškumas.</span>
                        </h1>

                        <p class="sefo-rise mt-8 max-w-[46ch] text-[1.05rem] leading-relaxed text-soot" style="--d: 460ms">
                            Rankomis formuoti burgeriai ir per naktį marinuota vištiena. Kepame tik tada, kai užsisakai —
                            niekas nelaukia po šildymo lempa.
                        </p>

                        <div class="sefo-rise mt-10 flex flex-wrap items-center gap-4" style="--d: 540ms">
                            <a href="#meniu" class="sefo-btn sefo-btn--flame">Pamatyti meniu</a>
                            <a href="{{ $phoneHref }}" class="sefo-btn sefo-btn--paper">Užsisakyti išsinešimui</a>
                        </div>

                        <dl class="sefo-rise mt-14 grid max-w-2xl grid-cols-3 border-t-[3px] border-ink pt-6" style="--d: 640ms">
                            @foreach ($stats as $stat)
                                <div @class([
                                    'pr-3' => ! $loop->last,
                                    'border-l-[3px] border-dashed border-ink/25 pl-4 sm:pl-7' => ! $loop->first,
                                ])>
                                    <dd class="sefo-display text-[clamp(1.9rem,4.4vw,2.9rem)] text-ink">
                                        {{ $stat['value'] }}<span class="text-flame">{{ $stat['unit'] }}</span>
                                    </dd>
                                    <dt class="sefo-label mt-2.5 text-[0.58rem] leading-snug text-soot/80">{{ $stat['label'] }}</dt>
                                </div>
                            @endforeach
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
                                alt="Šefo Užkandinė — greita ir skanu"
                                class="sefo-rise absolute inset-0 m-auto w-[74%]"
                                style="--d: 240ms"
                                width="1084"
                                height="1200"
                                fetchpriority="high"
                            >
                        </div>

                        <div class="sefo-float pointer-events-none absolute -bottom-12 -left-1 w-32 sm:w-40 lg:-left-8">
                            <x-site.art.burger class="w-full" />
                        </div>

                        <div class="sefo-frame-sm sefo-rise absolute -top-3 right-3 rotate-[7deg] bg-mustard px-4 py-3 text-center lg:-right-4" style="--d: 700ms">
                            <p class="sefo-label text-[0.54rem] text-ink/70">Burgeris nuo</p>
                            <p class="sefo-display mt-1.5 text-[1.7rem] text-ink">5,90 €</p>
                        </div>
                    </div>
                </div>
            </section>

            <x-site.marquee
                tone="ink"
                reverse
                class="border-b-[3px] border-ink"
                :items="['Smash', 'Sparneliai', 'Bekonas', 'Čederis', 'Strips', 'Bulvytės', 'Čili majonezas', 'Kojelės', 'Limonadas']"
            />

            {{-- Meniu --}}
            <section id="meniu" class="scroll-mt-24 border-b-[3px] border-ink bg-paper">
                <div class="mx-auto max-w-[1400px] px-5 py-20 lg:px-10 lg:py-28">
                    <div class="sefo-reveal flex flex-wrap items-end justify-between gap-8 border-b-[3px] border-ink pb-8">
                        <div>
                            <p class="sefo-label text-[0.64rem] text-flame">01 / Meniu</p>
                            <h2 class="sefo-display mt-4 text-[clamp(2.7rem,7vw,5.4rem)] text-ink">Burgeriai</h2>
                        </div>
                        <p class="max-w-[38ch] text-[0.95rem] leading-relaxed text-soot">
                            Bandelės kepamos vietoje, mėsa formuojama tą patį rytą. Jokių priedų, kurių nemokėtum
                            perskaityti garsiai.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-8 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($burgers as $item)
                            <x-site.menu-card
                                :name="$item['name']"
                                :description="$item['description']"
                                :price="$item['price']"
                                :weight="$item['weight']"
                                :art="$item['art']"
                                :tag="$item['tag']"
                                :delay="$loop->index * 90"
                            />
                        @endforeach
                    </div>

                    <div class="sefo-reveal mt-24 flex flex-wrap items-end justify-between gap-8 border-b-[3px] border-ink pb-8">
                        <div>
                            <p class="sefo-label text-[0.64rem] text-flame">02 / Meniu</p>
                            <h2 class="sefo-display mt-4 text-[clamp(2.7rem,7vw,5.4rem)] text-ink">Vištiena</h2>
                        </div>
                        <p class="max-w-[38ch] text-[0.95rem] leading-relaxed text-soot">
                            Marinuojame pasukose 24 valandas, tada apvoliojame savo prieskonių mišinyje. Traškumas
                            girdimas iš kito stalo.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-8 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($chicken as $item)
                            <x-site.menu-card
                                :name="$item['name']"
                                :description="$item['description']"
                                :price="$item['price']"
                                :weight="$item['weight']"
                                :art="$item['art']"
                                :tag="$item['tag']"
                                :delay="$loop->index * 90"
                            />
                        @endforeach
                    </div>

                    {{-- Priedai --}}
                    <div class="sefo-reveal sefo-frame mt-20 bg-ink text-cream">
                        <div class="grid gap-10 p-8 lg:grid-cols-12 lg:p-12">
                            <div class="lg:col-span-4">
                                <p class="sefo-label text-[0.64rem] text-mustard">03 / Priedai</p>
                                <h3 class="sefo-display mt-4 text-[clamp(2rem,4vw,3rem)]">Prie viso to</h3>
                                <p class="mt-4 max-w-[30ch] text-[0.9rem] leading-relaxed text-cream/70">
                                    Bulvytės kepamos dukart, padažai gaminami kas rytą.
                                </p>
                            </div>

                            <ul class="grid gap-x-12 lg:col-span-8 sm:grid-cols-2">
                                @foreach ($sides as $side)
                                    <li class="flex items-baseline gap-3 py-3">
                                        <span class="text-[0.95rem]">{{ $side['name'] }}</span>
                                        <span class="h-px flex-1 border-b-2 border-dotted border-cream/30"></span>
                                        <span class="sefo-display shrink-0 text-[1.05rem] text-mustard">{{ $side['price'] }} €</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Rinkinys --}}
            <section id="rinkiniai" class="relative scroll-mt-24 overflow-hidden border-b-[3px] border-ink bg-flame text-cream">
                <div class="sefo-halftone pointer-events-none absolute inset-0 text-ink"></div>

                <div class="relative mx-auto grid max-w-[1400px] items-center gap-14 px-5 py-20 lg:grid-cols-12 lg:gap-10 lg:px-10 lg:py-28">
                    <div class="sefo-reveal lg:col-span-7">
                        <p class="sefo-label text-[0.64rem] text-cream/80">04 / Rinkiniai</p>
                        <h2 class="sefo-display mt-5 text-[clamp(2.8rem,7.4vw,5.8rem)]">Šefo<br>rinkinys</h2>
                        <p class="mt-6 max-w-[42ch] text-[1.05rem] leading-relaxed text-cream/90">
                            Burgeris, bulvytės su šefo prieskoniais ir naminis limonadas. Vienas kvitas, nulis dvejojimų.
                        </p>

                        <ul class="mt-8 space-y-3.5">
                            @foreach (['Bet kuris burgeris iš meniu', 'Didelė porcija bulvyčių', 'Naminis limonadas arba gaivusis', 'Padažas pasirinkimui'] as $line)
                                <li class="flex items-center gap-4">
                                    <span class="sefo-display flex size-7 shrink-0 items-center justify-center bg-cream text-[0.85rem] text-flame">✓</span>
                                    <span class="text-[0.95rem] text-cream/90">{{ $line }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="#uzsakymas" class="sefo-btn sefo-btn--paper mt-10">Užsisakyti rinkinį</a>
                    </div>

                    <div class="sefo-reveal lg:col-span-5" style="--d: 140ms">
                        <div class="sefo-ticket sefo-frame mx-auto max-w-[420px] p-8 text-ink">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="sefo-label text-[0.58rem] text-soot/70">Kvitas Nr. 001</p>
                                    <p class="sefo-display mt-2 text-[1.9rem] leading-none">Rinkinys</p>
                                </div>
                                <span class="sefo-label bg-flame px-3 py-2 text-[0.6rem] text-cream">-17%</span>
                            </div>

                            <div class="mt-8 flex items-end justify-center gap-1">
                                <div class="w-20 shrink-0"><x-site.art.fries class="w-full" /></div>
                                <div class="w-32 shrink-0"><x-site.art.burger class="w-full" /></div>
                                <div class="w-18 shrink-0"><x-site.art.cup class="w-full" /></div>
                            </div>

                            <div class="sefo-dash mt-8 space-y-3 pt-6">
                                <div class="flex items-baseline justify-between">
                                    <span class="sefo-label text-[0.6rem] text-soot/70">Atskirai</span>
                                    <span class="sefo-display text-[1.1rem] text-soot/60 line-through">14,30 €</span>
                                </div>
                                <div class="flex items-baseline justify-between">
                                    <span class="sefo-label text-[0.68rem]">Rinkinyje</span>
                                    <span class="sefo-stamp text-[1.6rem] leading-none">11,90 €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Kaip veikia --}}
            <section id="kaip-veikia" class="relative scroll-mt-24 overflow-hidden border-b-[3px] border-ink bg-ink text-cream">
                <div class="sefo-grid-lines pointer-events-none absolute inset-0 opacity-30"></div>

                <div class="relative mx-auto max-w-[1400px] px-5 py-20 lg:px-10 lg:py-28">
                    <div class="sefo-reveal max-w-3xl">
                        <p class="sefo-label text-[0.64rem] text-mustard">05 / Kaip veikia</p>
                        <h2 class="sefo-display mt-5 text-[clamp(2.6rem,6.6vw,5rem)]">
                            Trys žingsniai iki <span class="text-flame">pirmo kąsnio</span>
                        </h2>
                    </div>

                    <div class="mt-16 grid gap-px border-[3px] border-cream/20 bg-cream/20 md:grid-cols-3">
                        @foreach ($steps as $step)
                            <div class="sefo-reveal group bg-ink p-8 transition-colors duration-300 hover:bg-char lg:p-10" style="--d: {{ $loop->index * 110 }}ms">
                                <span class="sefo-display block text-[clamp(3.4rem,7vw,5rem)] leading-none text-cream/15 transition-colors duration-300 group-hover:text-flame">
                                    {{ $step['no'] }}
                                </span>
                                <h3 class="sefo-display mt-6 text-[1.7rem]">{{ $step['title'] }}</h3>
                                <p class="mt-4 text-[0.95rem] leading-relaxed text-cream/70">{{ $step['text'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="sefo-reveal mt-14 flex flex-wrap items-center gap-6">
                        <div class="sefo-float w-28 shrink-0"><x-site.art.wings class="w-full" /></div>
                        <p class="sefo-label max-w-[46ch] text-[0.68rem] leading-relaxed text-cream/60">
                            Nespėji per pietų pertrauką? Paskambink iš anksto — rinkinys lauks ant prekystalio.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Atsiliepimai --}}
            <section id="atsiliepimai" class="scroll-mt-24 border-b-[3px] border-ink bg-paper">
                <div class="mx-auto max-w-[1400px] px-5 py-20 lg:px-10 lg:py-28">
                    <div class="sefo-reveal flex flex-wrap items-end justify-between gap-8">
                        <div>
                            <p class="sefo-label text-[0.64rem] text-flame">06 / Atsiliepimai</p>
                            <h2 class="sefo-display mt-4 text-[clamp(2.6rem,6.6vw,5rem)] text-ink">Ką sako<br>svečiai</h2>
                        </div>
                        <p class="sefo-display text-[clamp(2rem,4vw,3rem)] text-ink">
                            4,9<span class="text-flame">★</span>
                            <span class="sefo-label mt-2.5 block text-[0.58rem] text-soot/70">1 240 vertinimų</span>
                        </p>
                    </div>

                    <div class="mt-14 grid gap-8 md:grid-cols-3">
                        @foreach ($reviews as $review)
                            <figure class="sefo-reveal sefo-frame sefo-ticket flex flex-col p-8" style="--d: {{ $loop->index * 110 }}ms">
                                <div class="sefo-label flex items-center justify-between text-[0.56rem] text-soot/70">
                                    <span>Kvitas · {{ str_pad((string) ($loop->index + 12), 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[0.8rem] tracking-[0.2em] text-flame">★★★★★</span>
                                </div>

                                <blockquote class="sefo-display mt-6 text-[1.15rem] leading-[1.35] text-ink">
                                    „{{ $review['quote'] }}”
                                </blockquote>

                                <figcaption class="sefo-dash mt-auto flex items-center justify-between gap-4 pt-6">
                                    <span class="sefo-label text-[0.68rem] text-ink">{{ $review['author'] }}</span>
                                    <span class="sefo-label text-[0.54rem] text-soot/60">{{ $review['meta'] }}</span>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Kontaktai --}}
            <section id="kontaktai" class="scroll-mt-24 border-b-[3px] border-ink bg-cream">
                <div class="mx-auto grid max-w-[1400px] gap-14 px-5 py-20 lg:grid-cols-12 lg:px-10 lg:py-28">
                    <div class="sefo-reveal lg:col-span-5">
                        <p class="sefo-label text-[0.64rem] text-flame">07 / Kontaktai</p>
                        <h2 class="sefo-display mt-4 text-[clamp(2.4rem,5.6vw,4.2rem)] text-ink">Rasi mus<br>čia</h2>

                        <div class="mt-10 space-y-8">
                            <div>
                                <p class="sefo-label text-[0.58rem] text-soot/70">Adresas</p>
                                <p class="sefo-display mt-2 text-[1.4rem] text-ink">{{ $address }}</p>
                            </div>
                            <div>
                                <p class="sefo-label text-[0.58rem] text-soot/70">Telefonas</p>
                                <a href="{{ $phoneHref }}" class="sefo-display mt-2 block text-[1.4rem] text-flame transition-colors hover:text-ink">
                                    {{ $phone }}
                                </a>
                            </div>
                            <div>
                                <p class="sefo-label text-[0.58rem] text-soot/70">El. paštas</p>
                                <a href="mailto:{{ $email }}" class="sefo-display mt-2 block text-[1.4rem] text-ink transition-colors hover:text-flame">
                                    {{ $email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sefo-reveal lg:col-span-7" style="--d: 120ms">
                        <div class="sefo-frame bg-paper">
                            <div class="border-b-[3px] border-ink bg-ink px-8 py-5">
                                <p class="sefo-label text-[0.64rem] text-cream">Darbo laikas</p>
                            </div>

                            <dl class="px-8 py-3">
                                @foreach ($hours as $row)
                                    <div class="flex flex-wrap items-baseline gap-3 border-b-2 border-dashed border-ink/15 py-5 last:border-0">
                                        <dt class="sefo-label text-[0.64rem] text-soot">{{ $row['days'] }}</dt>
                                        <span class="h-px flex-1 border-b-2 border-dotted border-ink/15"></span>
                                        <dd class="sefo-display text-[1.15rem] text-ink">{{ $row['time'] }}</dd>
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
                                    <div>
                                        <p class="sefo-label text-[0.58rem] text-soot/70">Kioskas prie Savanorių</p>
                                        <p class="sefo-display mt-1.5 text-[1.15rem] text-ink">Nemokama stovėjimo vieta prie pat durų</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Užsakymas --}}
            <section id="uzsakymas" class="relative scroll-mt-24 overflow-hidden border-b-[3px] border-ink bg-mustard">
                <div class="sefo-halftone pointer-events-none absolute inset-0 text-ink"></div>

                <div class="relative mx-auto flex max-w-[1400px] flex-col items-center gap-8 px-5 py-20 text-center lg:px-10 lg:py-24">
                    <p class="sefo-reveal sefo-label text-[0.64rem] text-ink/70">Alkis nelaukia</p>
                    <h2 class="sefo-reveal sefo-display max-w-[24ch] text-[clamp(2.6rem,7vw,5.4rem)] text-ink" style="--d: 80ms">
                        Paskambink ir mes jau kepame
                    </h2>
                    <a href="{{ $phoneHref }}" class="sefo-reveal sefo-display text-[clamp(1.9rem,5.4vw,3.6rem)] text-flame underline decoration-[6px] underline-offset-[10px] transition-colors hover:text-ink" style="--d: 160ms">
                        {{ $phone }}
                    </a>
                    <p class="sefo-reveal max-w-[46ch] text-[0.95rem] leading-relaxed text-ink/70" style="--d: 220ms">
                        Išsinešimui arba pristatymui Kaune. Vidutinis laukimas — 12 minučių, piko metu iki 20.
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
                        alt="Šefo Užkandinė"
                        class="h-28 w-auto"
                        width="463"
                        height="512"
                        loading="lazy"
                    >
                    <p class="mt-6 max-w-[34ch] text-[0.95rem] leading-relaxed text-cream/60">
                        Šefo Užkandinė — burgeriai ir vištiena ant atviros ugnies. Greita ir skanu nuo 2014 metų.
                    </p>
                </div>

                <div class="lg:col-span-3">
                    <p class="sefo-label text-[0.58rem] text-mustard">Meniu</p>
                    <ul class="mt-5 space-y-3">
                        @foreach ($nav as $label => $href)
                            <li>
                                <a href="{{ $href }}" class="sefo-navlink text-[0.95rem] text-cream/80 hover:text-cream">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="lg:col-span-4">
                    <p class="sefo-label text-[0.58rem] text-mustard">Kontaktai</p>
                    <ul class="mt-5 space-y-3 text-[0.95rem] text-cream/80">
                        <li>{{ $address }}</li>
                        <li><a href="{{ $phoneHref }}" class="hover:text-cream">{{ $phone }}</a></li>
                        <li><a href="mailto:{{ $email }}" class="hover:text-cream">{{ $email }}</a></li>
                    </ul>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach (['Facebook', 'Instagram', 'Wolt'] as $channel)
                            <span class="sefo-label border-2 border-cream/25 px-3 py-2 text-[0.56rem] text-cream/70">{{ $channel }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <x-site.marquee
                tone="mustard"
                class="border-y-[3px] border-cream/15"
                :items="['Greita ir skanu', 'Burgeriai', 'Vištiena', 'Bulvytės', 'Rinkiniai', 'Iki pasimatymo']"
            />

            <div class="mx-auto flex max-w-[1400px] flex-wrap items-center justify-between gap-4 px-5 py-8 lg:px-10">
                <p class="sefo-label text-[0.56rem] text-cream/50">
                    © {{ now()->year }} Šefo Užkandinė · Visos teisės saugomos
                </p>

                <div class="sefo-label flex items-center gap-6 text-[0.56rem] text-cream/50">
                    <a href="#" class="hover:text-cream">Privatumas</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hover:text-cream">Valdymas</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-cream">Darbuotojams</a>
                    @endauth
                </div>
            </div>
        </footer>
    </body>
</html>
