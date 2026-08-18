<?php

use App\Livewire\Admin\Users;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $this->actingAs($this->admin);
});

test('an admin can create a user', function () {
    Livewire::test(Users::class)
        ->call('createUser')
        ->set('name', 'Nauja Vartotoja')
        ->set('email', 'nauja@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('is_admin', true)
        ->call('saveUser')
        ->assertHasNoErrors()
        ->assertSet('showFormModal', false);

    $this->assertDatabaseHas('users', [
        'name' => 'Nauja Vartotoja',
        'email' => 'nauja@example.com',
        'is_admin' => true,
    ]);
});

test('creating a user rejects a duplicate email', function () {
    $existing = User::factory()->create();

    Livewire::test(Users::class)
        ->call('createUser')
        ->set('name', 'Duplicate')
        ->set('email', $existing->email)
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('saveUser')
        ->assertHasErrors(['email']);

    expect(User::query()->where('email', $existing->email)->count())->toBe(1);
});

test('an admin can update a user without changing the password', function () {
    $user = User::factory()->create();
    $originalPassword = $user->password;

    Livewire::test(Users::class)
        ->call('editUser', $user->id)
        ->assertSet('name', $user->name)
        ->set('name', 'Atnaujinta')
        ->set('email', 'atnaujinta@example.com')
        ->set('is_admin', true)
        ->call('saveUser')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('Atnaujinta')
        ->and($user->email)->toBe('atnaujinta@example.com')
        ->and($user->is_admin)->toBeTrue()
        ->and($user->password)->toBe($originalPassword);
});

test('an admin can change another users password', function () {
    $user = User::factory()->create();
    $originalPassword = $user->password;

    Livewire::test(Users::class)
        ->call('editUser', $user->id)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('saveUser')
        ->assertHasNoErrors();

    expect($user->refresh()->password)->not->toBe($originalPassword);
});

test('an admin can delete another user', function () {
    $user = User::factory()->create();

    Livewire::test(Users::class)
        ->call('confirmDeleteUser', $user->id)
        ->assertSet('showDeleteModal', true)
        ->call('deleteUser')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('an admin cannot delete their own account', function () {
    Livewire::test(Users::class)
        ->call('confirmDeleteUser', $this->admin->id)
        ->call('deleteUser')
        ->assertHasErrors('delete');

    $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
});

test('the last admin cannot be deleted', function () {
    $other = User::factory()->admin()->create();

    $this->actingAs($other);

    $this->admin->delete();

    Livewire::test(Users::class)
        ->call('confirmDeleteUser', $other->id)
        ->call('deleteUser')
        ->assertHasErrors('delete');

    $this->assertDatabaseHas('users', ['id' => $other->id]);
});

test('the last admin cannot be demoted', function () {
    Livewire::test(Users::class)
        ->call('editUser', $this->admin->id)
        ->set('is_admin', false)
        ->call('saveUser')
        ->assertHasErrors('is_admin');

    expect($this->admin->refresh()->is_admin)->toBeTrue();
});

test('an admin can be demoted while another admin remains', function () {
    $other = User::factory()->admin()->create();

    Livewire::test(Users::class)
        ->call('editUser', $other->id)
        ->set('is_admin', false)
        ->call('saveUser')
        ->assertHasNoErrors();

    expect($other->refresh()->is_admin)->toBeFalse();
});
