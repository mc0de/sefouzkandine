<section class="w-full">
    @include('partials.admin-heading')

    <flux:heading class="sr-only">{{ __('Menu') }}</flux:heading>

    <x-admin.layout :heading="__('Menu')" :subheading="__('Manage the categories and items shown on the storefront')">
        <div class="flex items-center justify-between">
            <flux:heading size="lg">{{ __('Categories') }}</flux:heading>

            <flux:button variant="primary" icon="plus" wire:click="createCategory">{{ __('New category') }}</flux:button>
        </div>

        <div class="mt-4 overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Name (LT)') }}</flux:table.column>
                    <flux:table.column>{{ __('Name (EN)') }}</flux:table.column>
                    <flux:table.column>{{ __('Slug') }}</flux:table.column>
                    <flux:table.column>{{ __('Layout') }}</flux:table.column>
                    <flux:table.column>{{ __('Position') }}</flux:table.column>
                    <flux:table.column>{{ __('Items') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column />
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->categories as $category)
                        <flux:table.row :key="'category-'.$category->id">
                            <flux:table.cell>{{ $category->name_lt }}</flux:table.cell>
                            <flux:table.cell>{{ $category->name_en }}</flux:table.cell>
                            <flux:table.cell class="font-mono text-xs">{{ $category->slug }}</flux:table.cell>
                            <flux:table.cell>{{ $category->layout }}</flux:table.cell>
                            <flux:table.cell>{{ $category->position }}</flux:table.cell>
                            <flux:table.cell>{{ $category->items_count }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($category->is_active)
                                    <flux:badge color="green" size="sm" inset="top bottom">{{ __('Active') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm" inset="top bottom">{{ __('Hidden') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="text-end">
                                <flux:button size="sm" variant="ghost" icon="list-bullet" wire:click="selectCategory({{ $category->id }})">
                                    {{ __('Items') }}
                                </flux:button>

                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editCategory({{ $category->id }})">
                                    {{ __('Edit') }}
                                </flux:button>

                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="confirmDeleteCategory({{ $category->id }})">
                                    {{ __('Delete') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="8">{{ __('No categories yet.') }}</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <flux:separator class="my-8" variant="subtle" />

        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg">{{ __('Items') }}</flux:heading>

                <flux:subheading>
                    @if ($this->selectedCategory)
                        {{ __('Showing items in :category', ['category' => $this->selectedCategory->name_lt]) }}
                    @else
                        {{ __('Create a category to start adding items.') }}
                    @endif
                </flux:subheading>
            </div>

            @if ($this->selectedCategory)
                <flux:button variant="primary" icon="plus" wire:click="createItem">{{ __('New item') }}</flux:button>
            @endif
        </div>

        <div class="mt-4 overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Name (LT)') }}</flux:table.column>
                    <flux:table.column>{{ __('Name (EN)') }}</flux:table.column>
                    <flux:table.column>{{ __('Price') }}</flux:table.column>
                    <flux:table.column>{{ __('Artwork') }}</flux:table.column>
                    <flux:table.column>{{ __('Tag') }}</flux:table.column>
                    <flux:table.column>{{ __('Position') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column />
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->items as $item)
                        <flux:table.row :key="'item-'.$item->id">
                            <flux:table.cell>{{ $item->name_lt }}</flux:table.cell>
                            <flux:table.cell>{{ $item->name_en }}</flux:table.cell>
                            <flux:table.cell>{{ $item->price }}</flux:table.cell>
                            <flux:table.cell>{{ $item->art }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($item->tag)
                                    <flux:badge color="amber" size="sm" inset="top bottom">{{ $item->tag }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $item->position }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($item->is_active)
                                    <flux:badge color="green" size="sm" inset="top bottom">{{ __('Active') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm" inset="top bottom">{{ __('Hidden') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="text-end">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editItem({{ $item->id }})">
                                    {{ __('Edit') }}
                                </flux:button>

                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="confirmDeleteItem({{ $item->id }})">
                                    {{ __('Delete') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="8">{{ __('No items in this category yet.') }}</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <flux:modal wire:model="showCategoryModal" class="max-w-2xl">
            <form wire:submit="saveCategory" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingCategoryId ? __('Edit category') : __('Create category') }}
                    </flux:heading>

                    <flux:subheading>{{ __('Lithuanian and English copy are stored side by side.') }}</flux:subheading>
                </div>

                <flux:input wire:model="category_slug" :label="__('Slug')" type="text" required />

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input wire:model="category_name_lt" :label="__('Name (LT)')" type="text" required />
                    <flux:input wire:model="category_name_en" :label="__('Name (EN)')" type="text" required />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:textarea wire:model="category_description_lt" :label="__('Description (LT)')" rows="3" />
                    <flux:textarea wire:model="category_description_en" :label="__('Description (EN)')" rows="3" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:select wire:model="category_layout" :label="__('Layout')">
                        @foreach (\App\Models\MenuCategory::LAYOUTS as $layout)
                            <flux:select.option value="{{ $layout }}">{{ $layout }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:input wire:model="category_position" :label="__('Position')" type="number" min="0" required />
                </div>

                <div>
                    <flux:switch wire:model="category_is_active" :label="__('Visible on the storefront')" />
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button variant="filled" type="button" wire:click="closeCategoryModal">{{ __('Cancel') }}</flux:button>
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal wire:model="showCategoryDeleteModal" class="max-w-lg">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete category?') }}</flux:heading>

                    <flux:subheading>
                        {{ __('Deleting :category also permanently deletes the :count item(s) inside it.', ['category' => $deletingCategoryName, 'count' => $deletingCategoryItemCount]) }}
                    </flux:subheading>
                </div>

                <flux:callout variant="warning" icon="exclamation-triangle" :heading="__('This cannot be undone.')" />

                <div class="flex justify-end gap-2">
                    <flux:button variant="filled" type="button" wire:click="closeCategoryDeleteModal">{{ __('Cancel') }}</flux:button>
                    <flux:button variant="danger" type="button" wire:click="deleteCategory">{{ __('Delete category') }}</flux:button>
                </div>
            </div>
        </flux:modal>

        <flux:modal wire:model="showItemModal" class="max-w-2xl">
            <form wire:submit="saveItem" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingItemId ? __('Edit item') : __('Create item') }}
                    </flux:heading>

                    <flux:subheading>{{ __('Lithuanian and English copy are stored side by side.') }}</flux:subheading>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input wire:model="item_name_lt" :label="__('Name (LT)')" type="text" required />
                    <flux:input wire:model="item_name_en" :label="__('Name (EN)')" type="text" required />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:textarea wire:model="item_description_lt" :label="__('Description (LT)')" rows="3" />
                    <flux:textarea wire:model="item_description_en" :label="__('Description (EN)')" rows="3" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input wire:model="item_price" :label="__('Price')" type="number" step="0.01" min="0" required />
                    <flux:input wire:model="item_position" :label="__('Position')" type="number" min="0" required />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:select wire:model="item_art" :label="__('Artwork')" :placeholder="__('None')">
                        @foreach (\App\Models\MenuItem::ARTWORK as $art)
                            <flux:select.option value="{{ $art }}">{{ $art }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="item_tag" :label="__('Tag')" :placeholder="__('None')">
                        @foreach (\App\Models\MenuItem::TAGS as $tag)
                            <flux:select.option value="{{ $tag }}">{{ $tag }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:switch wire:model="item_is_active" :label="__('Visible on the storefront')" />
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button variant="filled" type="button" wire:click="closeItemModal">{{ __('Cancel') }}</flux:button>
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal wire:model="showItemDeleteModal" class="max-w-lg">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete item?') }}</flux:heading>

                    <flux:subheading>
                        {{ __('This permanently removes :item from the menu.', ['item' => $deletingItemName]) }}
                    </flux:subheading>
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button variant="filled" type="button" wire:click="closeItemDeleteModal">{{ __('Cancel') }}</flux:button>
                    <flux:button variant="danger" type="button" wire:click="deleteItem">{{ __('Delete item') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    </x-admin.layout>
</section>
