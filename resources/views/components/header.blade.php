<div class="flex justify-between items-center w-full">

    {{-- Bagian Kiri: Judul Dinamis atau Search Bar --}}
    @if(request()->is('admin/dashboard')) {{-- Lebih spesifik dengan nama route atau prefix admin --}}
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Admin Dashboard</p>
        </div>
    @elseif (request()->is('mentor/dashboard'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Mentor Dashboard</p>
        </div>
    @elseif (request()->is('student/dashboard'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Student Dashboard</p>
        </div>
    @elseif (request()->is('admin/users*'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">User Management</p>
        </div>
    @elseif (request()->is('admin/courses-management*'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Course Management (Admin)</p>
        </div>
    @elseif (request()->is('mentor/courses*') && !request()->is('mentor/courses/*/materials*') && !request()->is('mentor/courses/*/quizzes*'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Kelola Course Saya</p>
        </div>
    @elseif (request()->is('mentor/courses/*/materials*'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Kelola Materi</p>
        </div>
    @elseif (request()->is('mentor/courses/*/quizzes*'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Kelola Quiz</p>
        </div>
    @elseif (request()->is('courses/*') && !Str::contains(request()->path(), ['mentor', 'admin']))
         <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Detail Course</p>
        </div>
    @elseif (request()->is('courses'))
         <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Semua Course</p>
        </div>
     @elseif (request()->is('profile'))
         <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-xl sm:text-2xl md:text-3xl font-semibold">Edit Profil Saya</p>
        </div>
    {{-- Tambahkan kondisi lain jika perlu --}}
    @else
        {{-- Search Bar Default --}}
        <div class="flex items-center gap-4 px-6 py-3 rounded-full bg-white border border-[#4c5a73] w-full max-w-[658px]">
            <i class="fa-solid fa-magnifying-glass text-[#4c5a73] text-xl"></i>
            <input type="text" placeholder="Search your course...."
                class="bg-transparent outline-none text-[#4c5a73] text-xl w-full" />
        </div>
    @endif

    {{-- Bagian Kanan: Info User dengan Foto Profil --}}
    <div class="relative" x-data="{ open: false, roleOpen: false }">
        <div class="flex items-center gap-3 sm:gap-4"> {{-- Mengurangi gap sedikit --}}
            @php
                $user = Auth::user();
                $profilePictureUrl = asset('images/default_avatar.png'); // Default avatar

                if ($user->role === 'student' && $user->studentProfile && $user->studentProfile->profile_picture_path) {
                    $profilePictureUrl = Storage::url($user->studentProfile->profile_picture_path);
                } elseif ($user->role === 'mentor' && $user->mentorProfile && $user->mentorProfile->profile_picture_path) {
                    $profilePictureUrl = Storage::url($user->mentorProfile->profile_picture_path);
                }
                // Admin akan menggunakan default avatar kecuali jika Anda menambahkan logika foto profil untuk admin
            @endphp
            <img src="{{ $profilePictureUrl }}" alt="Foto Profil {{ $user->name }}" 
                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 shadow-sm cursor-pointer" 
                 @click="open = !open"> {{-- Foto profil bisa diklik buat buka dropdown --}}
            
            <span class="text-md sm:text-lg text-[#4c5a73] hidden sm:block">{{ $user->name }}</span> {{-- Ukuran teks disesuaikan, sembunyikan di layar kecil --}}
            
            <i class="fa-solid fa-chevron-down text-[#4c5a73] text-sm sm:text-lg cursor-pointer" @click="open = !open"></i>
        </div>

        {{-- Dropdown Menu (sama seperti kode lo sebelumnya) --}}
        <div x-show="open" @click.outside="open = false" x-cloak x-transition
            class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 border border-gray-200">
            {{-- Header Dropdown dengan Nama dan Role --}}
            <div class="px-4 py-3 border-b">
                <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
            </div>
            <ul class="py-1 text-sm text-gray-700">
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                        <i class="fa-solid fa-user-edit text-[#4c5a73] w-4 text-center"></i>
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a href="#" {{-- route('settings.index') --}} class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                        <i class="fa-solid fa-gear text-[#4c5a73] w-4 text-center"></i>
                        Settings
                    </a>
                </li>
                @if($user->isAdmin()) {{-- Pake helper method isAdmin() di model User lebih bersih --}}
                    <li>
                        <div class="relative">
                            <button @click="roleOpen = !roleOpen; open = true;" {{-- open = true biar dropdown utama gak nutup --}}
                                class="w-full flex items-center justify-between gap-3 px-4 py-2 hover:bg-gray-100 text-left">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-users-cog text-[#4c5a73] w-4 text-center"></i>
                                    Ganti Role
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-400" :class="{'rotate-90': roleOpen}"></i>
                            </button>

                            {{-- Popup ganti role --}}
                            <div x-show="roleOpen" @click.away="roleOpen = false" x-cloak x-transition
                                class="absolute right-full top-0 mr-1 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                                {{-- Ganti route('admin.update-role') dengan route yang benar jika ada --}}
                                <form method="POST" action="{{-- route('admin.update-role') --}}">
                                    @csrf
                                    <div class="space-y-1 p-2">
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded cursor-pointer text-sm">
                                            <input type="radio" name="role" value="admin"
                                                @checked($user->role === 'admin') class="form-radio text-indigo-600">
                                            Admin
                                        </label>
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded cursor-pointer text-sm">
                                            <input type="radio" name="role" value="mentor"
                                                @checked($user->role === 'mentor') class="form-radio text-indigo-600">
                                            Mentor
                                        </label>
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded cursor-pointer text-sm">
                                            <input type="radio" name="role" value="student"
                                                @checked($user->role === 'student') class="form-radio text-indigo-600">
                                            Student
                                        </label>
                                        <button type="submit"
                                            class="w-full text-left p-2 mt-1 text-indigo-600 hover:bg-indigo-50 rounded text-sm font-semibold">
                                            Simpan Role
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 hover:bg-gray-100 text-red-600">
                            <i class="fa-solid fa-right-from-bracket text-red-600 w-4 text-center"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>