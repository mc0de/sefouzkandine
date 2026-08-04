<?php

use App\Models\User;

it('renders the storefront landing page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Šefo Užkandinė', false)
        ->assertSee('Burgeriai', false)
        ->assertSee('Vištiena', false)
        ->assertSee('Šefo<br>rinkinys', false)
        ->assertSee(config('menu.phone'), false)
        ->assertSee(config('menu.address'), false);
});

it('lists every burger and chicken item with its price', function () {
    $response = $this->get(route('home'));

    foreach ([...config('menu.burgers'), ...config('menu.chicken')] as $item) {
        $response->assertSee($item['name'], false)
            ->assertSee($item['price'].' €', false);
    }
});

it('lists the sides and the opening hours', function () {
    $response = $this->get(route('home'));

    foreach (config('menu.sides') as $side) {
        $response->assertSee($side['name'], false);
    }

    foreach (config('menu.hours') as $row) {
        $response->assertSee($row['days'], false)
            ->assertSee($row['time'], false);
    }
});

it('shows guest reviews', function () {
    $response = $this->get(route('home'));

    foreach (config('menu.reviews') as $review) {
        $response->assertSee($review['quote'], false)
            ->assertSee($review['author'], false);
    }
});

it('links the staff area to login for guests', function () {
    $this->get(route('home'))
        ->assertSee('Darbuotojams', false)
        ->assertSee(route('login'), false);
});

it('links the staff area to the dashboard for signed in staff', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertSee('Valdymas', false)
        ->assertSee(route('dashboard'), false);
});
