<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

it('does not enable the registration feature', function () {
    expect(Features::enabled(Features::registration()))->toBeFalse();
});

it('does not register the registration routes', function () {
    expect(Route::has('register'))->toBeFalse()
        ->and(Route::has('register.store'))->toBeFalse();
});

it('does not respond to the registration endpoints', function () {
    $this->get('/register')->assertNotFound();

    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
});

it('renders the login page without a sign up link', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertDontSee('Sign up');
});
