<?php

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\OpeningHourSeeder;

beforeEach(function () {
    $this->seed([MenuSeeder::class, OpeningHourSeeder::class]);
});

test('the lithuanian storefront renders at the site root', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('lang="lt"', false)
        ->assertSee('Šviežia', false)
        ->assertSee('Greita', false)
        ->assertSee('Skanu', false)
        ->assertSee('Šviežia, Greita, Skanu', false)
        ->assertSee(config('site.address'), false)
        ->assertSee(config('site.phone'), false)
        ->assertSee('12:00–20:00', false);

    expect(app()->getLocale())->toBe('lt');
});

test('the lithuanian storefront lists every active menu item with its price', function () {
    $response = $this->get(route('home'))->assertOk();

    foreach (MenuItem::all() as $item) {
        $response->assertSee($item->translate('name', 'lt'))
            ->assertSee($item->formatted_price.' €', false);
    }

    foreach (MenuCategory::all() as $category) {
        $response->assertSee($category->translate('name', 'lt'));
    }

    // Sparnelių kategorijos aprašymas neša porcijos sudėtį — jis privalo likti matomas.
    $response->assertSee('8 sparnelių dalys + bulvytės + ranch/chipotle padažas');
});

test('the english storefront renders at the en prefix', function () {
    $response = $this->get(route('home.en'))->assertOk();

    expect(app()->getLocale())->toBe('en');

    $response->assertSee('lang="en"', false)
        ->assertSee('Fresh', false)
        ->assertSee('Fast', false)
        ->assertSee('Tasty', false)
        ->assertSee('Fresh, Fast, Tasty', false)
        ->assertSee('12:00–20:00', false);

    foreach (MenuItem::all() as $item) {
        $response->assertSee($item->translate('name', 'en'));
    }

    $response->assertSee('Wings')
        ->assertSee('Snacks')
        ->assertSee('8 wing pieces + fries + ranch/chipotle sauce');
});

test('both locales expose the call to order action', function (string $route, string $label) {
    $this->get(route($route))
        ->assertOk()
        ->assertSee($label, false)
        ->assertSee('tel:+37060267676', false);
})->with([
    'lithuanian' => ['home', 'Skambinti ir užsisakyti'],
    'english' => ['home.en', 'Call to order'],
]);

test('both locales link to each other for the language switcher', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('home.en'), false)
        ->assertSee('hreflang="x-default"', false)
        ->assertSee('rel="canonical"', false);

    $this->get(route('home.en'))
        ->assertOk()
        ->assertSee(route('home'), false);
});

test('hidden categories and items stay off the storefront', function () {
    $hiddenCategory = MenuCategory::factory()->hidden()->create([
        'name_lt' => 'PASLĖPTA KATEGORIJA',
        'name_en' => 'HIDDEN CATEGORY',
    ]);

    MenuItem::factory()->for($hiddenCategory, 'category')->create([
        'name_lt' => 'PASLĖPTOS KATEGORIJOS PATIEKALAS',
        'name_en' => 'HIDDEN CATEGORY ITEM',
    ]);

    $visibleCategory = MenuCategory::factory()->create([
        'name_lt' => 'MATOMA KATEGORIJA',
        'name_en' => 'VISIBLE CATEGORY',
    ]);

    MenuItem::factory()->hidden()->for($visibleCategory, 'category')->create([
        'name_lt' => 'PASLĖPTAS PATIEKALAS',
        'name_en' => 'HIDDEN ITEM',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('MATOMA KATEGORIJA')
        ->assertDontSee('PASLĖPTA KATEGORIJA')
        ->assertDontSee('PASLĖPTOS KATEGORIJOS PATIEKALAS')
        ->assertDontSee('PASLĖPTAS PATIEKALAS');
});

test('the storefront carries no marquee markup', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('marquee', false);

    $this->get(route('home.en'))
        ->assertOk()
        ->assertDontSee('marquee', false);
});

test('the storefront states no facts we cannot stand behind', function () {
    $response = $this->get(route('home'))->assertOk();

    foreach (['Kaunas', 'Kaune', 'nuo 2014', 'Savanorių', 'Pristatymas', 'atsiliepim', 'Burgeris', 'rinkinys'] as $claim) {
        $response->assertDontSee($claim, false);
    }
});

test('the storefront does not advertise the staff login', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Darbuotojams', false)
        ->assertDontSee(route('login'), false);
});

test('signed in staff still get a link to the admin', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Valdymas', false)
        ->assertSee(route('dashboard'), false);
});

test('it links the social profiles and shows the legal entity', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(config('site.social.facebook'), false)
        ->assertSee(config('site.social.instagram'), false)
        ->assertSee(config('site.company.name'), false)
        ->assertSee(config('site.company.code'), false)
        ->assertSee('"sameAs":["'.config('site.social.facebook').'"', false);
});

test('it points every map link at the configured place', function () {
    $body = $this->get(route('home'))->assertOk()->getContent();

    expect(substr_count($body, (string) config('site.maps')))->toBe(3)
        ->and($body)->not->toContain('maps.google.com')
        ->and($body)->not->toContain('maps/search');
});
