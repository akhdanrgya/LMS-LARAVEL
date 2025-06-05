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