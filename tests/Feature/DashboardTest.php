<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('the dashboard shows admin shortcuts to admins', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Jump straight to what you need')
        ->assertSee(route('admin.users'), false)
        ->assertSee(route('admin.menu'), false)
        ->assertSee(route('admin.hours'), false)
        ->assertSee(route('profile.edit'), false)
        ->assertSee(route('home'), false);
});

test('the dashboard hides admin shortcuts from everyone else', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee(route('admin.users'), false)
        ->assertDontSee(route('admin.menu'), false)
        ->assertDontSee(route('admin.hours'), false)
        ->assertSee(route('profile.edit'), false);
});

test('the shortcut icons keep a colour per section', function () {
    // Flux renders `icon` as a slot only when it is not a string; if that ever
    // changes the icons silently fall back to the default grey.
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('bg-blue-100', false)
        ->assertSee('bg-amber-100', false)
        ->assertSee('bg-emerald-100', false)
        ->assertSee('bg-violet-100', false)
        ->assertSee('bg-rose-100', false)
        ->assertSee('text-blue-500', false)
        ->assertSee('text-amber-500', false)
        ->assertSee('text-emerald-500', false);
});
