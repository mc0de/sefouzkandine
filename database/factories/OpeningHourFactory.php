<?php

namespace Database\Factories;

use App\Models\OpeningHour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OpeningHour>
 */
class OpeningHourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day_of_week' => fake()->unique()->numberBetween(1, 7),
            'opens_at' => '12:00',
            'closes_at' => '20:00',
            'is_closed' => false,
        ];
    }

    /**
     * Indicate that the restaurant is closed on this weekday.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'opens_at' => null,
            'closes_at' => null,
            'is_closed' => true,
        ]);
    }
}
