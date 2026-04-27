<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Load User seeder untuk admin login
        $this->call([
            UserSeeder::class,
        ]);

        $this->command->info('✅ Database seeding completed!');
    }
}
