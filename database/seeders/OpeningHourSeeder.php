<?php

namespace Database\Seeders;

use App\Models\OpeningHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpeningHourSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the opening hours — open every day from noon until eight.
     */
    public function run(): void
    {
        foreach (range(1, 7) as $day) {
            OpeningHour::updateOrCreate(
                ['day_of_week' => $day],
                ['opens_at' => '12:00', 'closes_at' => '20:00', 'is_closed' => false],
            );
        }
    }
}
