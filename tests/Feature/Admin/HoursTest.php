<?php

use App\Livewire\Admin\Hours;
use App\Models\OpeningHour;
use App\Models\User;
use Database\Seeders\OpeningHourSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('missing weekday rows are created on mount', function () {
    expect(OpeningHour::query()->count())->toBe(0);

    Livewire::test(Hours::class)->assertCount('days', 7);

    expect(OpeningHour::query()->count())->toBe(7)
        ->and(OpeningHour::query()->ordered()->pluck('day_of_week')->all())->toBe([1, 2, 3, 4, 5, 6, 7]);
});

test('an admin can save every weekday at once', function () {
    $component = Livewire::test(Hours::class);

    foreach (range(0, 6) as $index) {
        $component->set("days.{$index}.opens_at", '11:00')
            ->set("days.{$index}.closes_at", '23:00');
    }

    $component->call('save')->assertHasNoErrors();

    expect(OpeningHour::query()->count())->toBe(7);

    OpeningHour::query()->ordered()->get()->each(function (OpeningHour $hour) {
        expect(substr((string) $hour->opens_at, 0, 5))->toBe('11:00')
            ->and(substr((string) $hour->closes_at, 0, 5))->toBe('23:00')
            ->and((bool) $hour->is_closed)->toBeFalse();
    });
});

test('marking a day closed nulls its times', function () {
    Livewire::test(Hours::class)
        ->set('days.6.is_closed', true)
        ->call('save')
        ->assertHasNoErrors();

    $sunday = OpeningHour::query()->where('day_of_week', 7)->sole();

    expect((bool) $sunday->is_closed)->toBeTrue()
        ->and($sunday->opens_at)->toBeNull()
        ->and($sunday->closes_at)->toBeNull();
});

test('closing time must be after opening time', function () {
    Livewire::test(Hours::class)
        ->set('days.0.opens_at', '20:00')
        ->set('days.0.closes_at', '12:00')
        ->call('save')
        ->assertHasErrors('days.0.closes_at');

    $monday = OpeningHour::query()->where('day_of_week', 1)->sole();

    expect(substr((string) $monday->opens_at, 0, 5))->toBe('12:00');
});

test('an open day requires both times', function () {
    Livewire::test(Hours::class)
        ->set('days.0.opens_at', '')
        ->set('days.0.closes_at', '')
        ->call('save')
        ->assertHasErrors(['days.0.opens_at', 'days.0.closes_at']);
});

test('the form is prefilled with the stored opening hours', function () {
    $this->seed(OpeningHourSeeder::class);

    Livewire::actingAs(User::factory()->admin()->create())
        ->test(Hours::class)
        ->assertSet('days.0.day_of_week', 1)
        ->assertSet('days.0.opens_at', '12:00')
        ->assertSet('days.0.closes_at', '20:00')
        ->assertSet('days.6.day_of_week', 7)
        ->assertSet('days.6.opens_at', '12:00');
});
