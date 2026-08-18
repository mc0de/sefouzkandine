<?php

use App\Livewire\Admin\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('an admin can create a menu category', function () {
    Livewire::test(Menu::class)
        ->call('createCategory')
        ->set('category_slug', 'sparnai')
        ->set('category_name_lt', 'SPARNAI')
        ->set('category_name_en', 'WINGS')
        ->set('category_description_lt', 'Traškūs sparnai')
        ->set('category_description_en', 'Crispy wings')
        ->set('category_layout', 'list')
        ->set('category_position', 3)
        ->set('category_is_active', false)
        ->call('saveCategory')
        ->assertHasNoErrors()
        ->assertSet('showCategoryModal', false);

    $this->assertDatabaseHas('menu_categories', [
        'slug' => 'sparnai',
        'name_lt' => 'SPARNAI',
        'name_en' => 'WINGS',
        'layout' => 'list',
        'position' => 3,
        'is_active' => false,
    ]);
});

test('a menu category requires both language names', function () {
    Livewire::test(Menu::class)
        ->call('createCategory')
        ->set('category_slug', 'sparnai')
        ->set('category_name_lt', '')
        ->set('category_name_en', '')
        ->call('saveCategory')
        ->assertHasErrors(['category_name_lt', 'category_name_en']);

    expect(MenuCategory::query()->count())->toBe(0);
});

test('a menu category slug must be unique', function () {
    MenuCategory::factory()->create(['slug' => 'sparnai']);

    Livewire::test(Menu::class)
        ->call('createCategory')
        ->set('category_slug', 'sparnai')
        ->set('category_name_lt', 'SPARNAI')
        ->set('category_name_en', 'WINGS')
        ->call('saveCategory')
        ->assertHasErrors(['category_slug']);
});

test('an admin can update a menu category', function () {
    $category = MenuCategory::factory()->create();

    Livewire::test(Menu::class)
        ->call('editCategory', $category->id)
        ->assertSet('category_slug', $category->slug)
        ->set('category_name_lt', 'PAKEISTA')
        ->set('category_name_en', 'CHANGED')
        ->set('category_layout', 'list')
        ->call('saveCategory')
        ->assertHasNoErrors();

    $category->refresh();

    expect($category->name_lt)->toBe('PAKEISTA')
        ->and($category->name_en)->toBe('CHANGED')
        ->and($category->layout)->toBe('list');
});

test('deleting a menu category cascades to its items', function () {
    $category = MenuCategory::factory()->create();
    $item = MenuItem::factory()->for($category, 'category')->create();

    Livewire::test(Menu::class)
        ->call('confirmDeleteCategory', $category->id)
        ->assertSet('showCategoryDeleteModal', true)
        ->assertSet('deletingCategoryItemCount', 1)
        ->call('deleteCategory')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('menu_categories', ['id' => $category->id]);
    $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
});

test('an admin can create a menu item in the selected category', function () {
    $category = MenuCategory::factory()->create();

    Livewire::test(Menu::class)
        ->call('selectCategory', $category->id)
        ->call('createItem')
        ->set('item_name_lt', 'SPARNELIAI')
        ->set('item_name_en', 'WINGS')
        ->set('item_description_lt', 'Su padažu')
        ->set('item_description_en', 'With sauce')
        ->set('item_price', '7.50')
        ->set('item_art', 'wings')
        ->set('item_tag', 'hit')
        ->set('item_position', 2)
        ->call('saveItem')
        ->assertHasNoErrors()
        ->assertSet('showItemModal', false);

    $this->assertDatabaseHas('menu_items', [
        'menu_category_id' => $category->id,
        'name_lt' => 'SPARNELIAI',
        'name_en' => 'WINGS',
        'price' => '7.50',
        'art' => 'wings',
        'tag' => 'hit',
        'position' => 2,
    ]);
});

test('a menu item rejects a negative price', function () {
    $category = MenuCategory::factory()->create();

    Livewire::test(Menu::class)
        ->call('selectCategory', $category->id)
        ->call('createItem')
        ->set('item_name_lt', 'SPARNELIAI')
        ->set('item_name_en', 'WINGS')
        ->set('item_price', '-1')
        ->call('saveItem')
        ->assertHasErrors(['item_price']);

    expect(MenuItem::query()->count())->toBe(0);
});

test('a menu item requires both language names', function () {
    $category = MenuCategory::factory()->create();

    Livewire::test(Menu::class)
        ->call('selectCategory', $category->id)
        ->call('createItem')
        ->set('item_name_lt', '')
        ->set('item_name_en', '')
        ->set('item_price', '5')
        ->call('saveItem')
        ->assertHasErrors(['item_name_lt', 'item_name_en']);
});

test('an admin can update a menu item', function () {
    $item = MenuItem::factory()->create(['tag' => null]);

    Livewire::test(Menu::class)
        ->call('editItem', $item->id)
        ->assertSet('item_name_lt', $item->name_lt)
        ->set('item_name_lt', 'PAKEISTA')
        ->set('item_price', '9.99')
        ->set('item_tag', 'spicy')
        ->set('item_is_active', false)
        ->call('saveItem')
        ->assertHasNoErrors();

    $item->refresh();

    expect($item->name_lt)->toBe('PAKEISTA')
        ->and($item->price)->toBe('9.99')
        ->and($item->tag)->toBe('spicy')
        ->and($item->is_active)->toBeFalse();
});

test('an admin can delete a menu item', function () {
    $item = MenuItem::factory()->create();

    Livewire::test(Menu::class)
        ->call('confirmDeleteItem', $item->id)
        ->assertSet('showItemDeleteModal', true)
        ->call('deleteItem')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
});

test('a menu item stores no artwork or tag when none is picked', function () {
    $category = MenuCategory::factory()->create();

    Livewire::test(Menu::class)
        ->call('selectCategory', $category->id)
        ->call('createItem')
        ->set('item_name_lt', 'PAPRASTA')
        ->set('item_name_en', 'PLAIN')
        ->set('item_price', '5')
        ->set('item_art', '')
        ->set('item_tag', '')
        ->call('saveItem')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('menu_items', [
        'name_lt' => 'PAPRASTA',
        'art' => null,
        'tag' => null,
    ]);
});
