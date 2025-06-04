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
        // Panggil seeder lain sesuai urutan dependensi
        $this->call([
            UserSeeder::class,    // User dulu (termasuk profile mereka)
        ]);
    }
}