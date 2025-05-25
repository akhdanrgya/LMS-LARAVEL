<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User; // Buat ngambil mentor
use Illuminate\Support\Str; // Buat slug

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil mentor yang udah dibuat di UserSeeder
        $mentorBudi = User::where('email', 'budi.mentor@lms.test')->first();
        $mentorAni = User::where('email', 'ani.mentor@lms.test')->first();

        if ($mentorBudi) {
            Course::create([
                'mentor_id' => $mentorBudi->id,
                'title' => 'Belajar Laravel Dasar untuk Pemula',
                'slug' => Str::slug('Belajar Laravel Dasar untuk Pemula') . '-' . uniqid(),
                'description' => 'Course ini akan mengajarkan dasar-dasar framework Laravel dari nol hingga bisa membuat aplikasi sederhana.',
                // 'thumbnail_path' => 'path/to/thumbnail1.jpg', // Opsional
            ]);

            Course::create([
                'mentor_id' => $mentorBudi->id,
                'title' => 'Mahir JavaScript Modern (ES6+)',
                'slug' => Str::slug('Mahir JavaScript Modern (ES6+)') . '-' . uniqid(),
                'description' => 'Pelajari fitur-fitur terbaru JavaScript ES6 ke atas untuk membangun aplikasi web interaktif.',
            ]);
        }

        if ($mentorAni) {
            Course::create([
                'mentor_id' => $mentorAni->id,
                'title' => 'Strategi SEO Jitu 2025',
                'slug' => Str::slug('Strategi SEO Jitu 2025') . '-' . uniqid(),
                'description' => 'Kuasai teknik SEO terbaru untuk meningkatkan ranking website Anda di mesin pencari.',
            ]);
        }

        // Kalo mau, bisa tambahin seeder buat Materials dan Quizzes di sini,
        // atau bikin seeder terpisah (MaterialSeeder, QuizSeeder) terus panggil di DatabaseSeeder.php
        // setelah CourseSeeder.
    }
}