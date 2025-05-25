<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil student dan course yang udah dibuat
        $student1 = User::where('email', 'enrico@gmail.com')->first();
        $student2 = User::where('email', 'nadya@gmail.com')->first();

        $courseLaravel = Course::where('slug', 'like', 'belajar-laravel-dasar%')->first();
        $courseJS = Course::where('slug', 'like', 'mahir-javascript-modern%')->first();
        $courseSEO = Course::where('slug', 'like', 'strategi-seo-jitu%')->first();

        if ($student1 && $courseLaravel) {
            Enrollment::create([
                'student_id' => $student1->id,
                'course_id' => $courseLaravel->id,
                'enrolled_at' => now(),
            ]);
        }

        if ($student1 && $courseJS) {
            Enrollment::create([
                'student_id' => $student1->id,
                'course_id' => $courseJS->id,
                'enrolled_at' => now(),
                'completion_status' => 'completed', // Contoh udah selesai
                'completed_at' => now()->subDays(5), // Selesai 5 hari lalu
            ]);
        }

        if ($student2 && $courseLaravel) {
            Enrollment::create([
                'student_id' => $student2->id,
                'course_id' => $courseLaravel->id,
                'enrolled_at' => now()->subDays(2), // Enroll 2 hari lalu
            ]);
        }
         if ($student2 && $courseSEO) {
            Enrollment::create([
                'student_id' => $student2->id,
                'course_id' => $courseSEO->id,
                'enrolled_at' => now()->subDays(1),
            ]);
        }
    }
}