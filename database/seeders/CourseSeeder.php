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
        $mentor1 = User::where('email', 'akhdan@gmail.com')->first();
        $mentor2 = User::where('email', 'arvia@gmail.com')->first();

        if ($mentor1) {
            Course::create([
                'mentor_id' => $mentor1->id,
                'title' => 'Belajar Laravel Dasar untuk Pemula',
                'slug' => Str::slug('Belajar Laravel Dasar untuk Pemula') . '-' . uniqid(),
                'description' => 'Course ini akan mengajarkan dasar-dasar framework Laravel dari nol hingga bisa membuat aplikasi sederhana.',
                'thumbnail_path' => 'images/thumbnails/thumbnails_course.png', // Opsional
            ]);
            
            Course::create([
                'mentor_id' => $mentor1->id,
                'title' => 'Mahir JavaScript Modern (ES6+)',
                'slug' => Str::slug('Mahir JavaScript Modern (ES6+)') . '-' . uniqid(),
                'description' => 'Pelajari fitur-fitur terbaru JavaScript ES6 ke atas untuk membangun aplikasi web interaktif.',
                'thumbnail_path' => 'images/thumbnails/thumbnails_course.png', // Opsional
            ]);
        }

        if ($mentor2) {
            Course::create([
                'mentor_id' => $mentor2->id,
                'title' => 'Strategi SEO Jitu 2025',
                'slug' => Str::slug('Strategi SEO Jitu 2025') . '-' . uniqid(),
                'description' => 'Kuasai teknik SEO terbaru untuk meningkatkan ranking website Anda di mesin pencari.',
                'thumbnail_path' => 'images/thumbnails/thumbnails_course.png', // Opsional
            ]);
        }

        // Kalo mau, bisa tambahin seeder buat Materials dan Quizzes di sini,
        // atau bikin seeder terpisah (MaterialSeeder, QuizSeeder) terus panggil di DatabaseSeeder.php
        // setelah CourseSeeder.
    }
}