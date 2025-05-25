<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MentorProfile;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash; // Penting buat hash password

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Ganti passwordnya nanti!
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        // Admin biasanya gak punya profil mentor/student khusus, tapi kalo mau bisa ditambahin

        // 2. Buat beberapa Mentor
        $mentor1 = User::create([
            'name' => 'Akhdan',
            'email' => 'akhdan@gmail.com',
            'password' => Hash::make('akhdan123'),
            'role' => 'mentor',
            'email_verified_at' => now(),
        ]);
        MentorProfile::create([
            'user_id' => $mentor1->id,
            'bio' => 'Pengajar berpengalaman di bidang Web Development.',
            'expertise' => 'PHP, Laravel, JavaScript',
            'experience_years' => 5,
        ]);

        $mentor2 = User::create([
            'name' => 'Arvia',
            'email' => 'arvia@gmail.com',
            'password' => Hash::make('arvia123'),
            'role' => 'mentor',
            'email_verified_at' => now(),
        ]);
        MentorProfile::create([
            'user_id' => $mentor2->id,
            'bio' => 'Praktisi Digital Marketing dengan fokus di SEO dan Content.',
            'expertise' => 'Digital Marketing, SEO, Content Writing',
        ]);

        // 3. Buat beberapa Student
        $student1 = User::create([
            'name' => 'Enrico',
            'email' => 'enrico@gmail.com',
            'password' => Hash::make('enrico123'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
        StudentProfile::create([
            'student_id' => $student1->id,
            // total_score dan level bisa default dari migrasi
        ]);

        $student2 = User::create([
            'name' => 'Nadya',
            'email' => 'nadya@gmail.com',
            'password' => Hash::make('nadya123'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
        StudentProfile::create([
            'student_id' => $student2->id,
        ]);

        // Lo bisa tambahin lebih banyak user pake looping atau User Factory
        // Contoh pake User Factory (kalo udah dibikin):
        // User::factory(10)->create()->each(function ($user) {
        //     if ($user->role === 'student') {
        //         StudentProfile::factory()->create(['student_id' => $user->id]);
        //     } elseif ($user->role === 'mentor') {
        //         MentorProfile::factory()->create(['user_id' => $user->id]);
        //     }
        // });
    }
}