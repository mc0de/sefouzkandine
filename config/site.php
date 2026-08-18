<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kontaktai
    |--------------------------------------------------------------------------
    |
    | Menu items and opening hours live in the database and are edited from the
    | admin panel. Contact details rarely change, so they stay in config.
    |
    */

    'phone' => '+370 602 67676',
    'address' => 'Žaliųjų Ežerų g. 138, Vilnius (Balsiai)',
    'email' => 'labas@sefouzkandine.lt',

    /*
    |--------------------------------------------------------------------------
    | Kalbos
    |--------------------------------------------------------------------------
    |
    | The first locale is the default and is served from the site root; every
    | other locale is served from its own URL prefix (for example `/en`).
    |
    */

    'locales' => [
        'lt' => ['label' => 'Lietuvių', 'short' => 'LT', 'prefix' => null],
        'en' => ['label' => 'English', 'short' => 'EN', 'prefix' => 'en'],
    ],

];
