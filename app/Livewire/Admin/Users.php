<?php

namespace App\Livewire\Admin;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Manages the accounts that may sign in to the application.
 */
#[Title('Users')]
class Users extends Component
{
    use PasswordValidationRules, ProfileValidationRules, WithPagination;

    public bool $showFormModal = false;

    #[Locked]
    public ?int $editingUserId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $is_admin = false;

    public bool $showDeleteModal = false;

    #[Locked]
    public ?int $deletingUserId = null;

    #[Locked]
    public string $deletingUserName = '';

    /**
     * The users shown in the table.
     *
     * @return LengthAwarePaginator<int, User>
     */
    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()->orderBy('name')->orderBy('id')->paginate(15);
    }

    /**
     * Open the modal to create a brand new user.
     */
    public function createUser(): void
    {
        $this->resetForm();

        $this->showFormModal = true;
    }

    /**
     * Open the modal to edit an existing user.
     */
    public function editUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->resetForm();

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->isAdmin();

        $this->showFormModal = true;
    }

    /**
     * Create or update the user being edited.
     */
    public function saveUser(): void
    {
        $validated = $this->validate($this->userRules());

        if ($this->editingUserId === null) {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'is_admin' => $this->is_admin,
            ]);

            $this->closeFormModal();

            unset($this->users);

            Flux::toast(variant: 'success', text: __('User created.'));

            return;
        }

        $user = User::findOrFail($this->editingUserId);

        if ($user->isAdmin() && ! $this->is_admin && $this->isLastAdmin($user)) {
            $this->addError('is_admin', __('You cannot remove the last administrator.'));

            Flux::toast(variant: 'danger', text: __('You cannot remove the last administrator.'));

            return;
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $this->is_admin,
        ]);

        if (filled($this->password)) {
            $user->password = $validated['password'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->closeFormModal();

        unset($this->users);

        Flux::toast(variant: 'success', text: __('User updated.'));
    }

    /**
     * Ask for confirmation before deleting a user.
     */
    public function confirmDeleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->resetErrorBag();

        $this->deletingUserId = $user->id;
        $this->deletingUserName = $user->name;
        $this->showDeleteModal = true;
    }

    /**
     * Delete the user awaiting confirmation.
     */
    public function deleteUser(): void
    {
        if ($this->deletingUserId === null) {
            return;
        }

        $user = User::findOrFail($this->deletingUserId);

        if ($user->id === Auth::id()) {
            $this->guardrailFailed(__('You cannot delete your own account.'));

            return;
        }

        if ($this->isLastAdmin($user)) {
            $this->guardrailFailed(__('You cannot remove the last administrator.'));

            return;
        }

        $user->delete();

        $this->closeDeleteModal();

        unset($this->users);

        Flux::toast(variant: 'success', text: __('User deleted.'));
    }

    /**
     * Close the create/edit modal and forget its state.
     */
    public function closeFormModal(): void
    {
        $this->showFormModal = false;

        $this->resetForm();
    }

    /**
     * Close the delete confirmation modal and forget its state.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingUserId = null;
        $this->deletingUserName = '';

        $this->resetErrorBag();
    }

    /**
     * Report a blocked destructive action to the administrator.
     */
    protected function guardrailFailed(string $message): void
    {
        $this->addError('delete', $message);

        Flux::toast(variant: 'danger', text: $message);
    }

    /**
     * Determine whether the given user is the only remaining administrator.
     */
    protected function isLastAdmin(User $user): bool
    {
        return $user->isAdmin()
            && User::query()->admins()->whereKeyNot($user->getKey())->doesntExist();
    }

    /**
     * The rules used to validate the create/edit form.
     *
     * @return array<string, mixed>
     */
    protected function userRules(): array
    {
        $rules = [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($this->editingUserId),
            'is_admin' => ['boolean'],
        ];

        if ($this->editingUserId === null || filled($this->password)) {
            $rules['password'] = $this->passwordRules();
        }

        return $rules;
    }

    /**
     * Reset the create/edit form back to its empty state.
     */
    protected function resetForm(): void
    {
        $this->reset('editingUserId', 'name', 'email', 'password', 'password_confirmation', 'is_admin');

        $this->resetErrorBag();
    }
}
