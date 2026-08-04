<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kontaktai
    |--------------------------------------------------------------------------
    */

    'phone' => '+370 612 34 567',
    'address' => 'Savanorių pr. 124, Kaunas',
    'email' => 'labas@sefouzkandine.lt',

    'hours' => [
        ['days' => 'Pirmadienis–Ketvirtadienis', 'time' => '11:00–22:00'],
        ['days' => 'Penktadienis–Šeštadienis', 'time' => '11:00–24:00'],
        ['days' => 'Sekmadienis', 'time' => '12:00–21:00'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Meniu
    |--------------------------------------------------------------------------
    */

    'burgers' => [
        [
            'name' => 'Šefo burgeris',
            'description' => 'Rankomis suformuota mėsa, čederis, marinuoti agurkai ir šefo padažas švieže bandelėje.',
            'price' => '8,50',
            'weight' => '200 g mėsos',
            'art' => 'burger',
            'tag' => 'HITAS',
        ],
        [
            'name' => 'Dvigubas smash',
            'description' => 'Dvi plonos mėsos, prispaustos ant įkaitintos plytos. Dvigubas čederis ir svogūnai.',
            'price' => '10,90',
            'weight' => '2 × 110 g',
            'art' => 'smash',
            'tag' => null,
        ],
        [
            'name' => 'Ugnies burgeris',
            'description' => 'Čili majonezas, jalapenai, rūkyta paprika ir lydytas čederis. Kepa iš vidaus.',
            'price' => '9,40',
            'weight' => '200 g mėsos',
            'art' => 'burger',
            'tag' => 'AŠTRU',
        ],
        [
            'name' => 'BBQ bekono',
            'description' => 'Traškus bekonas, naminis BBQ padažas, karamelizuoti svogūnai, čederis.',
            'price' => '10,20',
            'weight' => '200 g mėsos',
            'art' => 'smash',
            'tag' => null,
        ],
    ],

    'chicken' => [
        [
            'name' => 'Traškus vištos burgeris',
            'description' => 'Per naktį marinuotas filė kepsnys, kopūstų salotos ir česnakinis padažas.',
            'price' => '8,90',
            'weight' => '180 g filė',
            'art' => 'chicken-burger',
            'tag' => 'NAUJAS',
        ],
        [
            'name' => 'Vištos kojelės',
            'description' => 'Marinuotos 24 valandas, keptos iki traškios plutelės. Penkios viename kibirėlyje.',
            'price' => '7,40',
            'weight' => '5 vnt.',
            'art' => 'wings',
            'tag' => null,
        ],
        [
            'name' => 'Vištienos strips',
            'description' => 'Šeši traškūs gabalėliai ir naminis česnakinis padažas be jokių kompromisų.',
            'price' => '6,90',
            'weight' => '6 vnt.',
            'art' => 'strips',
            'tag' => 'HITAS',
        ],
        [
            'name' => 'Korėjiški sparneliai',
            'description' => 'Gochujang glaistas, sezamo sėmenys ir žali svogūnai. Saldu, aštru, lipnu.',
            'price' => '8,60',
            'weight' => '8 vnt.',
            'art' => 'wings',
            'tag' => 'AŠTRU',
        ],
    ],

    'sides' => [
        ['name' => 'Bulvytės su šefo prieskoniais', 'price' => '2,90'],
        ['name' => 'Saldžiųjų bulvių fri', 'price' => '3,60'],
        ['name' => 'Svogūnų žiedai', 'price' => '3,40'],
        ['name' => 'Traški salotų dėžutė', 'price' => '3,20'],
        ['name' => 'Naminis limonadas', 'price' => '2,80'],
        ['name' => 'Padažai (česnakinis, BBQ, čili)', 'price' => '0,80'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atsiliepimai
    |--------------------------------------------------------------------------
    */

    'reviews' => [
        [
            'quote' => 'Geriausias smash burgeris Kaune. Bandelė šviežia, mėsa sultinga — per savaitę grįžau tris kartus.',
            'author' => 'Tomas G.',
            'meta' => 'Google · 5 žvaigždutės',
        ],
        [
            'quote' => 'Vištos kojelės traškios būtent taip, kaip reikia. O česnakinis padažas — atskira meilės istorija.',
            'author' => 'Ineta R.',
            'meta' => 'Facebook · 5 žvaigždutės',
        ],
        [
            'quote' => 'Užsisakiau išsinešti, po dvylikos minučių viskas buvo ant stalo. Greita ir tikrai skanu.',
            'author' => 'Mantas K.',
            'meta' => 'Google · 5 žvaigždutės',
        ],
    ],

];
