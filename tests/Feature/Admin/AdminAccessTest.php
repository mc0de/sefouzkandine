<?php

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\OpeningHour;
use App\Models\User;

test('guests are redirected to the login page', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with(['admin.users', 'admin.menu', 'admin.hours']);

test('non-admin users are forbidden', function (string $route) {
    $this->actingAs(User::factory()->create());

    $this->get(route($route))->assertForbidden();
})->with(['admin.users', 'admin.menu', 'admin.hours']);

test('admin users can reach every admin page', function (string $route) {
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route($route))->assertOk();
})->with(['admin.users', 'admin.menu', 'admin.hours']);

test('the admin root redirects to the users page', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->get('/admin')->assertRedirect('/admin/users');
});

test('the sidebar shows the admin group to admins', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Opening hours')
        ->assertSee(route('admin.users'));
});

test('the sidebar hides the admin group from non-admins', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Opening hours')
        ->assertDontSee(route('admin.users'));
});

test('the admin pages render with data present', function () {
    $this->actingAs(User::factory()->admin()->create());

    User::factory()->count(3)->create();
    $category = MenuCategory::factory()->create();
    MenuItem::factory()->count(2)->for($category, 'category')->create(['tag' => 'hit']);
    OpeningHour::factory()->create(['day_of_week' => 1]);

    $this->get(route('admin.users'))->assertOk()->assertSee('Admin');
    $this->get(route('admin.menu'))->assertOk()->assertSee($category->name_lt);
    $this->get(route('admin.hours'))->assertOk()->assertSee('Monday');
});

test('admin pages do not repeat the sidebar navigation', function () {
    $admin = User::factory()->admin()->create();

    foreach (['admin.users', 'admin.menu', 'admin.hours'] as $route) {
        $body = $this->actingAs($admin)->get(route($route))->assertOk()->getContent();

        expect(substr_count($body, route('admin.users')))->toBeLessThanOrEqual(2)
            ->and(substr_count($body, route('admin.hours')))->toBeLessThanOrEqual(2);
    }
});
