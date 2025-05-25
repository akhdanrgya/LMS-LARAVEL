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
        $studentCici = User::where('email', 'cici.student@lms.test')->first();
        $studentDodi = User::where('email', 'dodi.student@lms.test')->first();

        $courseLaravel = Course::where('slug', 'like', 'belajar-laravel-dasar%')->first();
        $courseJS = Course::where('slug', 'like', 'mahir-javascript-modern%')->first();
        $courseSEO = Course::where('slug', 'like', 'strategi-seo-jitu%')->first();

        if ($studentCici && $courseLaravel) {
            Enrollment::create([
                'student_id' => $studentCici->id,
                'course_id' => $courseLaravel->id,
                'enrolled_at' => now(),
            ]);
        }

        if ($studentCici && $courseJS) {
            Enrollment::create([
                'student_id' => $studentCici->id,
                'course_id' => $courseJS->id,
                'enrolled_at' => now(),
                'completion_status' => 'completed', // Contoh udah selesai
                'completed_at' => now()->subDays(5), // Selesai 5 hari lalu
            ]);
        }

        if ($studentDodi && $courseLaravel) {
            Enrollment::create([
                'student_id' => $studentDodi->id,
                'course_id' => $courseLaravel->id,
                'enrolled_at' => now()->subDays(2), // Enroll 2 hari lalu
            ]);
        }
         if ($studentDodi && $courseSEO) {
            Enrollment::create([
                'student_id' => $studentDodi->id,
                'course_id' => $courseSEO->id,
                'enrolled_at' => now()->subDays(1),
            ]);
        }
    }
}