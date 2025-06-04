<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Untuk upload foto profil
use Illuminate\Validation\Rule;          // Untuk validasi unique email
use Illuminate\Validation\Rules\Password;  // Untuk validasi password kompleks

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil user yang sedang login.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Eager load profile yang sesuai dengan role user
        // Ini akan memastikan $user->studentProfile atau $user->mentorProfile sudah ter-load jika ada
        // dan tidak akan error jika profilnya belum ada (akan jadi null).
        if ($user->role === 'student') {
            $user->loadMissing('studentProfile'); // loadMissing hanya load jika belum ter-load
        } elseif ($user->role === 'mentor') {
            $user->loadMissing('mentorProfile');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Mengupdate profil user yang sedang login.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi data dasar user
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'required_with:current_password', Password::min(8), 'confirmed'], // Ini yang dibenerin
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // 2MB Max
        ]);

        // Update data di tabel 'users'
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        // --- Validasi & Update Data Profil Sesuai Role ---
        if ($user->role === 'student') {
            // Untuk student, kita hanya handle upload foto profil di sini.
            // Field lain (total_score, level) di-manage oleh sistem, bukan input user.
            $profile = $user->studentProfile()->firstOrCreate(['student_id' => $user->id]);

            if ($request->hasFile('profile_picture')) {
                // Hapus foto lama jika ada
                if ($profile->profile_picture_path) {
                    Storage::disk('public')->delete($profile->profile_picture_path);
                }
                $profile->profile_picture_path = $request->file('profile_picture')->store('profile_pictures/students', 'public');
                $profile->save(); // Simpan student profile hanya jika ada perubahan foto
            }
            // Tidak ada field lain yang di-update dari request untuk student profile di sini

        } elseif ($user->role === 'mentor') {
            // Validasi tambahan khusus untuk field mentor
            $mentorProfileData = $request->validate([
                'bio' => 'nullable|string|max:1000',
                'expertise' => 'nullable|string|max:255',
                'experience_years' => 'nullable|integer|min:0|max:50',
                'linkedin_url' => 'nullable|url|max:255',
                'website_url' => 'nullable|url|max:255',
            ]);

            $profile = $user->mentorProfile()->firstOrCreate(['user_id' => $user->id]);
            
            // Update field mentor profile
            $profile->fill($mentorProfileData); // Mass assign data yang sudah divalidasi

            if ($request->hasFile('profile_picture')) {
                if ($profile->profile_picture_path) {
                    Storage::disk('public')->delete($profile->profile_picture_path);
                }
                $profile->profile_picture_path = $request->file('profile_picture')->store('profile_pictures/mentors', 'public');
            }
            $profile->save(); // Simpan mentor profile
        }
        
        $user->save(); // Simpan perubahan di user model (name, email, password jika ada)

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diupdate!');
    }
}