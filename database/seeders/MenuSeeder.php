<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the storefront menu.
     */
    public function run(): void
    {
        foreach ($this->categories() as $position => $category) {
            $items = $category['items'];
            unset($category['items']);

            $model = MenuCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [...$category, 'position' => $position, 'is_active' => true],
            );

            foreach ($items as $itemPosition => $item) {
                $model->items()->updateOrCreate(
                    ['name_lt' => $item['name_lt']],
                    [...$item, 'position' => $itemPosition, 'is_active' => true],
                );
            }
        }
    }

    /**
     * The menu as it hangs above the counter.
     *
     * @return list<array{slug: string, name_lt: string, name_en: string, description_lt: string|null, description_en: string|null, layout: string, items: list<array<string, mixed>>}>
     */
    protected function categories(): array
    {
        return [
            [
                'slug' => 'sparneliai',
                'name_lt' => 'Sparneliai',
                'name_en' => 'Wings',
                'description_lt' => '8 sparnelių dalys + bulvytės + ranch/chipotle padažas',
                'description_en' => '8 wing pieces + fries + ranch/chipotle sauce',
                'layout' => 'cards',
                'items' => [
                    ['name_lt' => 'BBQ', 'name_en' => 'BBQ', 'price' => 10, 'art' => 'wings-bbq', 'tag' => 'hit'],
                    ['name_lt' => 'Teriyaki', 'name_en' => 'Teriyaki', 'price' => 10, 'art' => 'wings-teriyaki', 'tag' => null],
                    ['name_lt' => 'Šefo', 'name_en' => "Chef's", 'price' => 10, 'art' => 'wings-chef', 'tag' => null],
                    ['name_lt' => 'Sweet Chilli Mango', 'name_en' => 'Sweet Chilli Mango', 'price' => 10, 'art' => 'wings-mango', 'tag' => 'spicy'],
                ],
            ],
            [
                'slug' => 'bulvytes',
                'name_lt' => 'Bulvytės',
                'name_en' => 'Fries',
                'description_lt' => null,
                'description_en' => null,
                'layout' => 'cards',
                'items' => [
                    ['name_lt' => 'Tex-Mex bulvytės', 'name_en' => 'Tex-Mex fries', 'price' => 7, 'art' => 'fries-tex-mex', 'tag' => null],
                    ['name_lt' => 'Klasikinės bulvytės', 'name_en' => 'Classic fries', 'price' => 5, 'art' => 'fries', 'tag' => null],
                ],
            ],
            [
                'slug' => 'uzkandziai',
                'name_lt' => 'Užkandžiai',
                'name_en' => 'Snacks',
                'description_lt' => null,
                'description_en' => null,
                'layout' => 'cards',
                'items' => [
                    ['name_lt' => 'Vištienos rutuliukai', 'name_en' => 'Chicken balls', 'price' => 7, 'art' => 'strips', 'tag' => null],
                    ['name_lt' => 'Svogūnų žiedai', 'name_en' => 'Onion rings', 'price' => 6, 'art' => 'onion-rings', 'tag' => null],
                ],
            ],
            [
                'slug' => 'gerimai',
                'name_lt' => 'Gėrimai',
                'name_en' => 'Drinks',
                'description_lt' => null,
                'description_en' => null,
                'layout' => 'list',
                'items' => [
                    ['name_lt' => 'Pepsi / 7Up / Mirinda', 'name_en' => 'Pepsi / 7Up / Mirinda', 'price' => 3, 'art' => 'cup', 'tag' => null],
                    ['name_lt' => 'Gazuotas vanduo', 'name_en' => 'Sparkling water', 'price' => 2, 'art' => 'cup', 'tag' => null],
                    ['name_lt' => 'Nefiltruota gira', 'name_en' => 'Unfiltered kvass', 'price' => 4, 'art' => 'cup', 'tag' => null],
                ],
            ],
        ];
    }
}
