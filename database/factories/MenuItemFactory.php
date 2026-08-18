<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word().' '.fake()->word();

        return [
            'menu_category_id' => MenuCategory::factory(),
            'name_lt' => Str::upper($name),
            'name_en' => Str::upper($name),
            'description_lt' => fake()->sentence(),
            'description_en' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 2, 15),
            'art' => fake()->randomElement(MenuItem::ARTWORK),
            'tag' => null,
            'position' => 0,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the item is hidden from the storefront.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => ['is_active' => false]);
    }
}
