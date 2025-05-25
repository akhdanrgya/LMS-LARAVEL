<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace-nya

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile; // Untuk buat profil student
use App\Models\MentorProfile;  // Untuk buat profil mentor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // Untuk validasi, misal unique email & enum role
use Illuminate\Validation\Rules\Password; // Untuk validasi password yang lebih kuat

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15); // Ambil semua user, urutkan berdasarkan nama, paginasi
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'string', Rule::in(['admin', 'mentor', 'student'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Admin yang buat, anggap langsung verified
        ]);

        // Buat profil jika role-nya student atau mentor
        if ($user->role === 'student') {
            StudentProfile::create(['student_id' => $user->id]);
        } elseif ($user->role === 'mentor') {
            MentorProfile::create(['user_id' => $user->id]);
            // Di sini admin bisa juga langsung isi bio/expertise mentor kalo ada field-nya di form
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'User baru (' . $user->name . ') berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data user.
     * Method show() biasanya tidak terlalu diperlukan jika ada edit.
     * Jika Route::resource() tidak di-except('show'), method ini akan ada.
     */
    // public function show(User $user)
    // {
    //     // return view('admin.users.show', compact('user'));
    // }


    /**
     * Menampilkan form untuk mengedit data user.
     */
    public function edit(User $user) // Route model binding otomatis ngambil user berdasarkan ID di URL
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user yang ada di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], // Unik, kecuali email user itu sendiri
            'role' => ['required', 'string', Rule::in(['admin', 'mentor', 'student'])],
            'password' => ['nullable', 'confirmed', Password::min(8)], // Password opsional, kalo diisi baru diupdate
        ]);

        $currentRole = $user->role; // Simpan role lama

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Logika handle perubahan role dan pembuatan/penghapusan profil
        if ($currentRole !== $user->role) {
            // Hapus profil lama jika ada
            if ($currentRole === 'student' && $user->studentProfile) {
                $user->studentProfile->delete();
            } elseif ($currentRole === 'mentor' && $user->mentorProfile) {
                $user->mentorProfile->delete();
            }

            // Buat profil baru jika role baru adalah student atau mentor
            if ($user->role === 'student' && !$user->studentProfile) {
                StudentProfile::create(['student_id' => $user->id]);
            } elseif ($user->role === 'mentor' && !$user->mentorProfile) {
                MentorProfile::create(['user_id' => $user->id]);
            }
        }


        return redirect()->route('admin.users.index')
                         ->with('success', 'Data user (' . $user->name . ') berhasil diupdate.');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        // Aturan tambahan: Admin tidak bisa menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Profil (StudentProfile/MentorProfile) akan otomatis terhapus jika relasinya di Model User
        // menggunakan onDelete('cascade') atau jika sudah di-handle di migrasi profile (foreign key cascade).
        // Jika tidak, perlu dihapus manual:
        // if ($user->studentProfile) $user->studentProfile->delete();
        // if ($user->mentorProfile) $user->mentorProfile->delete();
        // (Di migrasi kita, StudentProfile & MentorProfile sudah cascadeOnDelete)

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User (' . $userName . ') berhasil dihapus.');
    }
}