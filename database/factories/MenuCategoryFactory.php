<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuCategory>
 */
class MenuCategoryFactory extends Factory
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
            'slug' => Str::slug($name),
            'name_lt' => Str::upper($name),
            'name_en' => Str::upper($name),
            'description_lt' => fake()->sentence(),
            'description_en' => fake()->sentence(),
            'layout' => 'cards',
            'position' => 0,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the category renders as a compact price list.
     */
    public function list(): static
    {
        return $this->state(fn (array $attributes): array => ['layout' => 'list']);
    }

    /**
     * Indicate that the category is hidden from the storefront.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => ['is_active' => false]);
    }
}
