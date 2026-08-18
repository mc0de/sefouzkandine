<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // The demo account has a known password, so it never leaves a developer
        // machine. Elsewhere the first admin is made with `php artisan admin:create`.
        if (app()->isLocal()) {
            User::factory()->admin()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            MenuSeeder::class,
            OpeningHourSeeder::class,
        ]);
    }
}
