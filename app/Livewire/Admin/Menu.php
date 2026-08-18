<?php

namespace App\Livewire\Admin;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Manages the storefront menu: its categories and the items inside them.
 */
#[Title('Menu')]
class Menu extends Component
{
    #[Locked]
    public ?int $selectedCategoryId = null;

    public bool $showCategoryModal = false;

    #[Locked]
    public ?int $editingCategoryId = null;

    public string $category_slug = '';

    public string $category_name_lt = '';

    public string $category_name_en = '';

    public string $category_description_lt = '';

    public string $category_description_en = '';

    public string $category_layout = 'cards';

    public int $category_position = 0;

    public bool $category_is_active = true;

    public bool $showCategoryDeleteModal = false;

    #[Locked]
    public ?int $deletingCategoryId = null;

    #[Locked]
    public string $deletingCategoryName = '';

    #[Locked]
    public int $deletingCategoryItemCount = 0;

    public bool $showItemModal = false;

    #[Locked]
    public ?int $editingItemId = null;

    public string $item_name_lt = '';

    public string $item_name_en = '';

    public string $item_description_lt = '';

    public string $item_description_en = '';

    public string $item_price = '';

    public ?string $item_art = null;

    public ?string $item_tag = null;

    public int $item_position = 0;

    public bool $item_is_active = true;

    public bool $showItemDeleteModal = false;

    #[Locked]
    public ?int $deletingItemId = null;

    #[Locked]
    public string $deletingItemName = '';

    /**
     * Select the first category so the item table is never empty on arrival.
     */
    public function mount(): void
    {
        $this->selectedCategoryId = $this->firstCategoryId();
    }

    /**
     * The id of the first category in storefront order, if there is one.
     */
    protected function firstCategoryId(): ?int
    {
        $id = MenuCategory::query()->ordered()->value('id');

        return $id === null ? null : (int) $id;
    }

    /**
     * Every category, in storefront order.
     *
     * @return Collection<int, MenuCategory>
     */
    #[Computed]
    public function categories(): Collection
    {
        return MenuCategory::query()->ordered()->withCount('items')->get();
    }

    /**
     * The category whose items are currently being managed.
     */
    #[Computed]
    public function selectedCategory(): ?MenuCategory
    {
        if ($this->selectedCategoryId === null) {
            return null;
        }

        return MenuCategory::query()->find($this->selectedCategoryId);
    }

    /**
     * The items belonging to the selected category.
     *
     * @return Collection<int, MenuItem>
     */
    #[Computed]
    public function items(): Collection
    {
        $category = $this->selectedCategory();

        if ($category === null) {
            /** @var Collection<int, MenuItem> $empty */
            $empty = new Collection;

            return $empty;
        }

        return $category->items()->get();
    }

    /**
     * Show the items belonging to the given category.
     */
    public function selectCategory(int $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;

        unset($this->selectedCategory, $this->items);
    }

    /**
     * Open the modal to create a brand new category.
     */
    public function createCategory(): void
    {
        $this->resetCategoryForm();

        $this->category_position = (int) $this->categories()->max('position') + 1;
        $this->showCategoryModal = true;
    }

    /**
     * Open the modal to edit an existing category.
     */
    public function editCategory(int $categoryId): void
    {
        $category = MenuCategory::findOrFail($categoryId);

        $this->resetCategoryForm();

        $this->editingCategoryId = $category->id;
        $this->category_slug = $category->slug;
        $this->category_name_lt = $category->name_lt;
        $this->category_name_en = $category->name_en;
        $this->category_description_lt = (string) $category->description_lt;
        $this->category_description_en = (string) $category->description_en;
        $this->category_layout = $category->layout;
        $this->category_position = $category->position;
        $this->category_is_active = $category->is_active;

        $this->showCategoryModal = true;
    }

    /**
     * Create or update the category being edited.
     */
    public function saveCategory(): void
    {
        $validated = $this->validate([
            'category_slug' => [
                'required', 'string', 'max:255', 'alpha_dash',
                Rule::unique(MenuCategory::class, 'slug')->ignore($this->editingCategoryId),
            ],
            'category_name_lt' => ['required', 'string', 'max:255'],
            'category_name_en' => ['required', 'string', 'max:255'],
            'category_description_lt' => ['nullable', 'string', 'max:1000'],
            'category_description_en' => ['nullable', 'string', 'max:1000'],
            'category_layout' => ['required', 'string', Rule::in(MenuCategory::LAYOUTS)],
            'category_position' => ['required', 'integer', 'min:0'],
            'category_is_active' => ['boolean'],
        ]);

        $attributes = [
            'slug' => $validated['category_slug'],
            'name_lt' => $validated['category_name_lt'],
            'name_en' => $validated['category_name_en'],
            'description_lt' => blank($validated['category_description_lt']) ? null : $validated['category_description_lt'],
            'description_en' => blank($validated['category_description_en']) ? null : $validated['category_description_en'],
            'layout' => $validated['category_layout'],
            'position' => $validated['category_position'],
            'is_active' => $validated['category_is_active'],
        ];

        if ($this->editingCategoryId === null) {
            $category = MenuCategory::create($attributes);

            $this->selectedCategoryId = $category->id;
        } else {
            MenuCategory::findOrFail($this->editingCategoryId)->update($attributes);
        }

        $this->closeCategoryModal();

        $this->refreshMenu();

        Flux::toast(variant: 'success', text: __('Category saved.'));
    }

    /**
     * Ask for confirmation before deleting a category and its items.
     */
    public function confirmDeleteCategory(int $categoryId): void
    {
        $category = MenuCategory::query()->withCount('items')->findOrFail($categoryId);

        $this->deletingCategoryId = $category->id;
        $this->deletingCategoryName = $category->name_lt;
        $this->deletingCategoryItemCount = (int) $category->items_count;
        $this->showCategoryDeleteModal = true;
    }

    /**
     * Delete the category awaiting confirmation, cascading to its items.
     */
    public function deleteCategory(): void
    {
        if ($this->deletingCategoryId === null) {
            return;
        }

        MenuCategory::findOrFail($this->deletingCategoryId)->delete();

        if ($this->selectedCategoryId === $this->deletingCategoryId) {
            $this->selectedCategoryId = $this->firstCategoryId();
        }

        $this->closeCategoryDeleteModal();

        $this->refreshMenu();

        Flux::toast(variant: 'success', text: __('Category deleted.'));
    }

    /**
     * Close the category create/edit modal.
     */
    public function closeCategoryModal(): void
    {
        $this->showCategoryModal = false;

        $this->resetCategoryForm();
    }

    /**
     * Close the category delete confirmation modal.
     */
    public function closeCategoryDeleteModal(): void
    {
        $this->showCategoryDeleteModal = false;
        $this->deletingCategoryId = null;
        $this->deletingCategoryName = '';
        $this->deletingCategoryItemCount = 0;
    }

    /**
     * Open the modal to create a brand new item in the selected category.
     */
    public function createItem(): void
    {
        $this->resetItemForm();

        $this->item_position = (int) $this->items()->max('position') + 1;
        $this->showItemModal = true;
    }

    /**
     * Open the modal to edit an existing item.
     */
    public function editItem(int $itemId): void
    {
        $item = MenuItem::findOrFail($itemId);

        $this->resetItemForm();

        $this->selectCategory($item->menu_category_id);

        $this->editingItemId = $item->id;
        $this->item_name_lt = $item->name_lt;
        $this->item_name_en = $item->name_en;
        $this->item_description_lt = (string) $item->description_lt;
        $this->item_description_en = (string) $item->description_en;
        $this->item_price = (string) $item->price;
        $this->item_art = $item->art;
        $this->item_tag = $item->tag;
        $this->item_position = $item->position;
        $this->item_is_active = $item->is_active;

        $this->showItemModal = true;
    }

    /**
     * Create or update the item being edited.
     */
    public function saveItem(): void
    {
        if ($this->selectedCategoryId === null) {
            $this->addError('item_name_lt', __('Create a category before adding items.'));

            return;
        }

        $validated = $this->validate([
            'item_name_lt' => ['required', 'string', 'max:255'],
            'item_name_en' => ['required', 'string', 'max:255'],
            'item_description_lt' => ['nullable', 'string', 'max:1000'],
            'item_description_en' => ['nullable', 'string', 'max:1000'],
            'item_price' => ['required', 'numeric', 'min:0'],
            'item_art' => ['nullable', 'string', Rule::in(MenuItem::ARTWORK)],
            'item_tag' => ['nullable', 'string', Rule::in(MenuItem::TAGS)],
            'item_position' => ['required', 'integer', 'min:0'],
            'item_is_active' => ['boolean'],
        ]);

        $attributes = [
            'menu_category_id' => $this->selectedCategoryId,
            'name_lt' => $validated['item_name_lt'],
            'name_en' => $validated['item_name_en'],
            'description_lt' => blank($validated['item_description_lt']) ? null : $validated['item_description_lt'],
            'description_en' => blank($validated['item_description_en']) ? null : $validated['item_description_en'],
            'price' => $validated['item_price'],
            'art' => blank($validated['item_art']) ? null : $validated['item_art'],
            'tag' => blank($validated['item_tag']) ? null : $validated['item_tag'],
            'position' => $validated['item_position'],
            'is_active' => $validated['item_is_active'],
        ];

        if ($this->editingItemId === null) {
            MenuItem::create($attributes);
        } else {
            MenuItem::findOrFail($this->editingItemId)->update($attributes);
        }

        $this->closeItemModal();

        $this->refreshMenu();

        Flux::toast(variant: 'success', text: __('Item saved.'));
    }

    /**
     * Ask for confirmation before deleting an item.
     */
    public function confirmDeleteItem(int $itemId): void
    {
        $item = MenuItem::findOrFail($itemId);

        $this->deletingItemId = $item->id;
        $this->deletingItemName = $item->name_lt;
        $this->showItemDeleteModal = true;
    }

    /**
     * Delete the item awaiting confirmation.
     */
    public function deleteItem(): void
    {
        if ($this->deletingItemId === null) {
            return;
        }

        MenuItem::findOrFail($this->deletingItemId)->delete();

        $this->closeItemDeleteModal();

        $this->refreshMenu();

        Flux::toast(variant: 'success', text: __('Item deleted.'));
    }

    /**
     * Close the item create/edit modal.
     */
    public function closeItemModal(): void
    {
        $this->showItemModal = false;

        $this->resetItemForm();
    }

    /**
     * Close the item delete confirmation modal.
     */
    public function closeItemDeleteModal(): void
    {
        $this->showItemDeleteModal = false;
        $this->deletingItemId = null;
        $this->deletingItemName = '';
    }

    /**
     * Drop the cached category and item lookups so the table re-reads them.
     */
    protected function refreshMenu(): void
    {
        unset($this->categories, $this->selectedCategory, $this->items);
    }

    /**
     * Reset the category form back to its empty state.
     */
    protected function resetCategoryForm(): void
    {
        $this->reset(
            'editingCategoryId',
            'category_slug',
            'category_name_lt',
            'category_name_en',
            'category_description_lt',
            'category_description_en',
            'category_layout',
            'category_position',
            'category_is_active',
        );

        $this->resetErrorBag();
    }

    /**
     * Reset the item form back to its empty state.
     */
    protected function resetItemForm(): void
    {
        $this->reset(
            'editingItemId',
            'item_name_lt',
            'item_name_en',
            'item_description_lt',
            'item_description_en',
            'item_price',
            'item_art',
            'item_tag',
            'item_position',
            'item_is_active',
        );

        $this->resetErrorBag();
    }
}
