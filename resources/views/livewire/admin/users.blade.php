<section class="w-full">
    @include('partials.admin-heading')

    <flux:heading class="sr-only">{{ __('Users') }}</flux:heading>

    <x-admin.layout :heading="__('Users')" :subheading="__('Manage the accounts that can sign in to this application')">
        <div class="flex justify-end">
            <flux:button variant="primary" icon="plus" wire:click="createUser">{{ __('New user') }}</flux:button>
        </div>

        <div class="mt-6 overflow-x-auto">
            <flux:table :paginate="$this->users">
                <flux:table.columns>
                    <flux:table.column>{{ __('Name') }}</flux:table.column>
                    <flux:table.column>{{ __('Email') }}</flux:table.column>
                    <flux:table.column>{{ __('Role') }}</flux:table.column>
                    <flux:table.column>{{ __('Verified') }}</flux:table.column>
                    <flux:table.column>{{ __('Created') }}</flux:table.column>
                    <flux:table.column />
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->users as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell class="whitespace-nowrap">{{ $user->name }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($user->isAdmin())
                                    <flux:badge color="lime" size="sm" inset="top bottom">{{ __('Admin') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm" inset="top bottom">{{ __('Member') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($user->email_verified_at)
                                    <flux:badge color="green" size="sm" inset="top bottom">{{ __('Verified') }}</flux:badge>
                                @else
                                    <flux:badge color="amber" size="sm" inset="top bottom">{{ __('Unverified') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">{{ $user->created_at?->format('Y-m-d') }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap text-end">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editUser({{ $user->id }})">
                                    {{ __('Edit') }}
                                </flux:button>

                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="confirmDeleteUser({{ $user->id }})">
                                    {{ __('Delete') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>

        <flux:modal wire:model="showFormModal" class="max-w-lg">
            <form wire:submit="saveUser" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingUserId ? __('Edit user') : __('Create user') }}
                    </flux:heading>

                    <flux:subheading>
                        {{ __('Accounts marked as admin can reach this panel.') }}
                    </flux:subheading>
                </div>

                <flux:input wire:model="name" :label="__('Name')" type="text" required autocomplete="off" />

                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="off" />

                <flux:input
                    wire:model="password"
                    :label="$editingUserId ? __('New password (leave blank to keep current)') : __('Password')"
                    type="password"
                    autocomplete="new-password"
                    viewable
                />

                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    autocomplete="new-password"
                    viewable
                />

                <div>
                    <flux:switch wire:model="is_admin" :label="__('Administrator')" />
                    <flux:error name="is_admin" />
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button variant="filled" type="button" wire:click="closeFormModal">{{ __('Cancel') }}</flux:button>
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal wire:model="showDeleteModal" class="max-w-lg">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete user?') }}</flux:heading>

                    <flux:subheading>
                        {{ __('This permanently removes :name and everything attached to the account.', ['name' => $deletingUserName]) }}
                    </flux:subheading>
                </div>

                @error('delete')
                    <flux:callout variant="danger" icon="exclamation-triangle" :heading="$message" />
                @enderror

                <div class="flex justify-end gap-2">
                    <flux:button variant="filled" type="button" wire:click="closeDeleteModal">{{ __('Cancel') }}</flux:button>
                    <flux:button variant="danger" type="button" wire:click="deleteUser">{{ __('Delete user') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    </x-admin.layout>
</section>
