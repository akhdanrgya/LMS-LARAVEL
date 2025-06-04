@extends('layouts.layout') {{-- Sesuaikan dengan layout utama lo --}}

@section('title', 'Profil: ' . $user->name)

@push('styles')
<style>
    .profile-avatar-public {
        width: 150px; height: 150px;
        border-radius: 50%; object-fit: cover;
        border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .course-card-sm { /* Untuk daftar course di profil mentor */
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    .course-card-sm:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .course-card-sm img {
        width: 100%; height: 120px; object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        
        {{-- Header Profil (Bagian Umum) --}}
        <div class="bg-white rounded-xl shadow-xl p-6 md:p-8 mb-8 text-center relative">
            @php
                $profilePicture = asset('images/default_avatar.png'); // Default avatar
                if ($user->role === 'mentor' && $user->mentorProfile && $user->mentorProfile->profile_picture_path) {
                    $profilePicture = Storage::url($user->mentorProfile->profile_picture_path);
                } elseif ($user->role === 'student' && $user->studentProfile && $user->studentProfile->profile_picture_path) {
                    $profilePicture = Storage::url($user->studentProfile->profile_picture_path);
                }
            @endphp
            <img src="{{ $profilePicture }}" 
                 alt="Foto Profil {{ $user->name }}" 
                 class="profile-avatar-public mx-auto @if($user->role === 'mentor' || $user->role === 'student') -mt-20 @else -mt-16 @endif mb-4">
            
            <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
            <p class="text-md text-indigo-600 font-semibold">{{ ucfirst($user->role) }}</p>

            {{-- Tombol Edit Profil, hanya muncul jika user melihat profilnya sendiri --}}
            @if(Auth::check() && Auth::id() == $user->id)
                <div class="absolute top-4 right-4">
                    <a href="{{ route('profile.edit') }}" class="bg-yellow-400 hover:bg-yellow-500 text-yellow-800 font-semibold py-2 px-4 rounded-lg text-xs shadow">
                        Edit Profil Saya
                    </a>
                </div>
            @endif
        </div>

        {{-- KONTEN SPESIFIK ROLE --}}

        {{-- Jika yang Dilihat adalah Profil MENTOR --}}
        @if($user->role === 'mentor' && $user->mentorProfile)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Kolom Kiri: Bio & Kontak --}}
                <div class="md:col-span-1 space-y-6">
                    @if($user->mentorProfile->bio)
                    <div class="bg-white rounded-xl shadow-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Tentang Saya</h2>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($user->mentorProfile->bio)) !!}
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-xl shadow-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Info & Kontak</h2>
                        @if($user->mentorProfile->expertise)
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-brain fa-fw mr-2 text-yellow-500"></i> <strong class="text-gray-800">Keahlian:</strong> {{ $user->mentorProfile->expertise }}
                        </p>
                        @endif
                        @if($user->mentorProfile->experience_years)
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="fas fa-briefcase fa-fw mr-2 text-green-500"></i> Pengalaman: {{ $user->mentorProfile->experience_years }} tahun
                        </p>
                        @endif
                        @if($user->mentorProfile->linkedin_url)
                        <p class="mb-1">
                            <a href="{{ $user->mentorProfile->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline break-all">
                                <i class="fab fa-linkedin fa-fw mr-2"></i> LinkedIn Profile
                            </a>
                        </p>
                        @endif
                        @if($user->mentorProfile->website_url)
                        <p>
                            <a href="{{ $user->mentorProfile->website_url }}" target="_blank" class="text-gray-700 hover:text-gray-900 hover:underline break-all">
                                <i class="fas fa-globe fa-fw mr-2"></i> Website Pribadi
                            </a>
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Kolom Kanan: Daftar Course yang Diajar --}}
                <div class="md:col-span-2">
                    <div class="bg-white rounded-xl shadow-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Course yang Diajar ({{ $user->taughtCourses->count() }})</h2>
                        @if($user->taughtCourses->isEmpty())
                            <p class="text-gray-600">Mentor ini belum mempublikasikan course apapun.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach($user->taughtCourses as $course)
                                <div class="course-card-sm flex flex-col">
                                    <a href="{{ route('courses.show', $course->slug) }}">
                                        @if($course->thumbnail_path)
                                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}">
                                        @else
                                            <div class="w-full h-[120px] bg-gray-200 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="p-3 flex flex-col flex-grow">
                                        <h3 class="text-md font-semibold text-gray-800 mb-1">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-indigo-600">{{ Str::limit($course->title, 40) }}</a>
                                        </h3>
                                        <div class="mt-auto pt-1">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold">
                                                Lihat Detail &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        {{-- Jika yang Dilihat adalah Profil STUDENT --}}
        @elseif($user->role === 'student' && $user->studentProfile)
            <div class="bg-white rounded-xl shadow-xl p-6 md:p-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Student</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Skor:</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $user->studentProfile->total_score ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Level:</p>
                        <p class="text-2xl font-bold text-green-600">Level {{ $user->studentProfile->level ?? 1 }}</p>
                    </div>
                </div>
                {{-- Di sini bisa ditambahin info lain yang "publik" buat student, misalnya daftar course yang diikutinya (judul aja), --}}
                {{-- atau achievement/badge kalo ada. Tapi hati-hati soal privasi. --}}
                {{-- 
                @if($user->enrolledCourses->isNotEmpty())
                <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-2 border-t pt-4">Course yang Diikuti:</h3>
                <ul class="list-disc list-inside text-gray-600 text-sm">
                    @foreach($user->enrolledCourses->take(5) as $enrolledCourse)
                        <li>
                            <a href="{{ route('courses.show', $enrolledCourse->slug) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $enrolledCourse->title }}
                            </a>
                        </li>
                    @endforeach
                    @if($user->enrolledCourses->count() > 5)
                        <li>Dan lainnya...</li>
                    @endif
                </ul>
                @endif
                --}}
                <p class="mt-6 text-sm text-gray-500 italic">Informasi detail lainnya bersifat pribadi.</p>
            </div>
        
        {{-- Jika yang Dilihat adalah Profil ADMIN (atau role lain yang belum ada profil spesifik) --}}
        @elseif($user->role === 'admin')
             <div class="bg-white rounded-xl shadow-xl p-6 md:p-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Admin</h2>
                <p class="text-gray-700">Ini adalah akun Administrator.</p>
                <p class="text-sm text-gray-600 mt-1">Email: <a href="mailto:{{ $user->email }}" class="text-indigo-600 hover:underline">{{ $user->email }}</a></p>
             </div>
        @else
            <div class="bg-white rounded-xl shadow-xl p-6 md:p-8">
                <p class="text-gray-700">Profil untuk role ini belum memiliki detail spesifik.</p>
            </div>
        @endif

    </div>
</div>
@endsection