<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

test('it creates a verified admin', function () {
    $this->artisan('admin:create', [
        '--name' => 'Console Admin',
        '--email' => 'console@example.com',
        '--password' => 'a-very-long-password-123',
    ])->assertSuccessful();

    $user = User::query()->where('email', 'console@example.com')->sole();

    expect($user->name)->toBe('Console Admin')
        ->and($user->is_admin)->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->password)->not->toBe('a-very-long-password-123');
});

test('it asks before promoting an existing user and honours a no', function () {
    $user = User::factory()->create(['email' => 'existing@example.com']);

    $this->artisan('admin:create', ['--email' => 'existing@example.com'])
        ->expectsConfirmation('existing@example.com already exists. Grant this account admin rights?', 'no')
        ->assertFailed();

    expect($user->fresh()->is_admin)->toBeFalse();
});

test('it promotes an existing user when the prompt is confirmed', function () {
    $user = User::factory()->create(['email' => 'existing@example.com']);

    $this->artisan('admin:create', ['--email' => 'existing@example.com'])
        ->expectsConfirmation('existing@example.com already exists. Grant this account admin rights?', 'yes')
        ->assertSuccessful();

    expect($user->fresh()->is_admin)->toBeTrue();
});

test('it promotes an existing user with the promote flag, without asking', function () {
    $user = User::factory()->unverified()->create(['email' => 'existing@example.com']);

    $this->artisan('admin:create', ['--email' => 'existing@example.com', '--promote' => true])
        ->assertSuccessful();

    $user->refresh();

    expect($user->is_admin)->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull();
});

test('it leaves an existing admin alone', function () {
    $user = User::factory()->admin()->create(['email' => 'boss@example.com']);

    $this->artisan('admin:create', ['--email' => 'boss@example.com'])->assertSuccessful();

    expect($user->fresh()->is_admin)->toBeTrue();
});

test('it rejects a weak or invalid submission', function (array $options) {
    $this->artisan('admin:create', $options)->assertFailed();

    expect(User::query()->count())->toBe(0);
})->with([
    'invalid email' => [['--name' => 'A', '--email' => 'not-an-email', '--password' => 'a-very-long-password-123']],
    'empty name' => [['--name' => '', '--email' => 'a@example.com', '--password' => 'a-very-long-password-123']],
]);

test('it explains which option is missing when run without interaction', function () {
    $this->withoutMockingConsoleOutput();

    $status = Artisan::call('admin:create', ['--no-interaction' => true]);

    expect($status)->toBe(1)
        ->and(Artisan::output())->toContain('The --email option is required when running without interaction.');
});
