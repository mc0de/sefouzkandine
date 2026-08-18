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

    'company' => ['name' => 'MB „Skonio taškas“', 'code' => '307551322'],
    'phone' => '+370 602 67676',
    'address' => 'Žaliųjų Ežerų g. 138, Vilnius (Balsiai)',
    'email' => 'labas@sefouzkandine.lt',
    'maps' => 'https://maps.app.goo.gl/eBDhmG6Lo9gvVEL86',

    /*
    |--------------------------------------------------------------------------
    | Socialiniai tinklai
    |--------------------------------------------------------------------------
    |
    | Raktas turi atitikti `site.social.*` vertimą ir ženkliuką
    | x-site.social-links komponente. Tuščią reikšmę palikus, nuoroda nerodoma.
    |
    */

    'social' => [
        'facebook' => 'https://www.facebook.com/sefouzkandine/',
        'instagram' => 'https://www.instagram.com/sefouzkandine/',
    ],

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
