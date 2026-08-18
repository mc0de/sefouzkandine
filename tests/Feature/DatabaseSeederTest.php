<?php

use App\Models\MenuCategory;
use App\Models\OpeningHour;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

/** `db:seed` asks for confirmation in production, so force it past the guard. */
function seedDatabase(string $environment): void
{
    app()->detectEnvironment(fn (): string => $environment);

    test()->artisan('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true])
        ->assertSuccessful();
}

test('it seeds the demo admin on a local machine', function () {
    seedDatabase('local');

    expect(User::query()->where('email', 'test@example.com')->sole()->is_admin)->toBeTrue();
});

test('it does not seed the demo admin anywhere else', function (string $environment) {
    seedDatabase($environment);

    expect(User::query()->where('email', 'test@example.com')->exists())->toBeFalse()
        ->and(User::query()->count())->toBe(0);
})->with(['production', 'staging', 'testing']);

test('it always seeds the menu and the opening hours', function (string $environment) {
    seedDatabase($environment);

    expect(MenuCategory::query()->count())->toBe(4)
        ->and(OpeningHour::query()->count())->toBe(7);
})->with(['local', 'production']);
