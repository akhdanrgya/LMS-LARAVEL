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
            CourseSeeder::class,  // Baru Course (butuh mentor dari UserSeeder)
            EnrollmentSeeder::class, // Terakhir Enrollment (butuh student & course)
            // Tambahin seeder lain di sini kalo ada, misal MaterialSeeder, QuizSeeder, dll.
        ]);
    }
}