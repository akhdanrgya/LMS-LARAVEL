@extends('layouts.layout') {{-- GANTI 'layouts.app' dengan nama file layout utama lo kalo beda --}}

@section('title', $course->title . ' - LMS Kita')

@section('content')
@include('components.header')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @include('layouts.partials.alerts') {{-- Nampilin notifikasi session --}}

        {{-- Judul Course dan Info Mentor --}}
        <div class="mb-8 p-6 bg-white rounded-xl shadow-lg">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-3">{{ $course->title }}</h1>
            <p>Mentor: 
                <a href="{{ route('profiles.show', $course->mentor->id) }}" class="text-indigo-600 hover:underline">
                    {{ $course->mentor->name ?? 'N/A' }}
                </a>
            </p>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <i class="fas fa-users mr-2"></i> {{ $course->students_count ?? $course->students->count() }} siswa
                terdaftar
                <span class="mx-2">|</span>
                <i class="fas fa-star text-yellow-400 mr-1"></i>
                {{ number_format($course->average_rating, 1) ?? 'Belum ada rating' }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Utama (Deskripsi, Materi, Quiz, Tombol Aksi) --}}
            <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-xl shadow-lg">

                {{-- Thumbnail Course --}}
                @if($course->thumbnail_path)
                    <img src="{{ $course->thumbnail_url }}" alt="Thumbnail {{ $course->title }}"
                        class="w-full h-auto sm:h-96 object-cover rounded-lg mb-6 shadow-md">
                @else
                    <div
                        class="w-full h-64 sm:h-96 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg mb-6 shadow-md">
                        <i class="fas fa-image fa-4x"></i>
                    </div>
                @endif

                {{-- Deskripsi Course --}}
                <div class="prose max-w-none mb-8"> {{-- 'prose' class dari Tailwind Typography buat styling teks --}}
                    <h2 class="text-2xl font-semibold text-gray-700 mb-3">Deskripsi Course</h2>
                    {!! nl2br(e($course->description)) !!} {{-- nl2br buat jaga baris baru, e() buat escape HTML --}}
                </div>

                {{-- Tombol Aksi (Enroll, Lanjutkan Belajar, Login/Register) --}}
                <div class="border-t pt-6 mt-6">
                    @auth {{-- Cek apakah user login --}}
                        @if(Auth::user()->role == 'student') {{-- Cek apakah rolenya student --}}
                            @php
                                // Cek apakah student ini sudah enroll ke course ini
                                $isEnrolled = Auth::user()->enrollments()->where('course_id', $course->id)->exists();
                            @endphp

                            @if($isEnrolled)
                                @auth {{-- Pastiin user login --}}
                                    @if($isEnrolled || Auth::user()->role === 'mentor' || Auth::user()->role === 'admin') {{-- Tampilkan
                                        jika enrolled, atau mentor/admin --}}
                                        <div class="bg-white p-6 rounded-xl shadow-xl mb-8">
                                            <h2 class="text-2xl font-semibold text-gray-700 mb-5">Materi Course</h2>
                                            @if($course->materials->isEmpty())
                                                <p class="text-gray-600">Belum ada materi untuk course ini.</p>
                                            @else
                                                <div class="space-y-3">
                                                    @foreach ($course->materials as $material)
                                                        <div class="border p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                                            <div class="flex justify-between items-center">
                                                                <div>
                                                                    <h3 class="text-lg font-medium text-gray-800 flex items-center">
                                                                        @if($material->content_type == 'text')
                                                                            <i class="fas fa-file-alt fa-fw mr-2 text-blue-500"></i>
                                                                        @elseif($material->content_type == 'video_link')
                                                                            <i class="fab fa-youtube fa-fw mr-2 text-red-500"></i>
                                                                        @elseif($material->content_type == 'file_path')
                                                                            <i class="fas fa-file-download fa-fw mr-2 text-green-500"></i>
                                                                        @endif
                                                                        {{ $material->title }}
                                                                    </h3>
                                                                    <p class="text-xs text-gray-500">Tipe:
                                                                        {{ ucfirst(str_replace('_', ' ', $material->content_type)) }}</p>
                                                                </div>
                                                                <div class="ml-4 flex-shrink-0">
                                                                    {{-- Tombol "Lihat Materi" ini akan ngarah ke halaman detail materi --}}
                                                                    <a href="{{ route('student.courses.materials.show', ['course' => $course->slug, 'material' => $material->id]) }}"
                                                                        class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-3 rounded-md text-sm shadow hover:shadow-md transition-all">
                                                                        Lihat Materi
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                            @else
                                <form action="{{ route('student.courses.enroll', $course->slug) }}" method="POST"
                                    class="text-center sm:text-left">
                                    @csrf
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out text-lg">
                                        Daftar ke Course Ini <i class="fas fa-user-plus ml-2"></i>
                                    </button>
                                </form>
                            @endif
                        @elseif(Auth::user()->role == 'mentor' && Auth::id() == $course->mentor_id)
                            <div class="text-center sm:text-left">
                                <a href="{{ route('mentor.courses.edit', $course->slug) }}"
                                    class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all text-lg">
                                    Edit Course Ini <i class="fas fa-pencil-alt ml-2"></i>
                                </a>
                                <p class="text-sm text-yellow-700 mt-3"><i class="fas fa-info-circle mr-1"></i>Ini adalah course
                                    yang Anda ajar.</p>
                            </div>
                        @elseif(Auth::user()->role == 'admin')
                            <div class="text-center sm:text-left">
                                <a href="{{ route('admin.courses.manage.edit', $course->id) }}"
                                    class="inline-block bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all text-lg">
                                    Kelola Course (Admin) <i class="fas fa-cog ml-2"></i>
                                </a>
                            </div>
                        @else
                            {{-- Untuk role lain atau user yang login tapi bukan student/mentor pemilik/admin --}}
                            <p class="text-center sm:text-left text-gray-600">Login sebagai student untuk mendaftar ke course ini.
                            </p>
                        @endif
                    @endauth

                    @guest {{-- Kalo user belum login --}}
                        <div class="text-center sm:text-left">
                            <p class="text-gray-700 mb-3">Ingin mendaftar ke course ini?</p>
                            <a href="{{ route('login') }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all mr-2">
                                Login
                            </a>
                            <span class="text-gray-600 mx-1">atau</span>
                            <a href="{{ route('register') }}"
                                class="text-indigo-600 hover:text-indigo-800 font-semibold py-3 px-6 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition-all">
                                Register
                            </a>
                        </div>
                    @endguest
                </div>

            </div>

            {{-- Kolom Sidebar (Materi, Quiz) --}}
            <aside class="lg:col-span-1 space-y-6">
                {{-- Daftar Materi --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-3">Materi Course</h3>
                    @if($course->materials && $course->materials->count() > 0)
                        <ul class="space-y-3">
                            @foreach ($course->materials->sortBy('order_sequence') as $material)
                                <li class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors">
                                    @php
                                        $icon = 'fa-file-alt'; // default
                                        if ($material->content_type == 'video_link')
                                            $icon = 'fa-video';
                                        if ($material->content_type == 'quiz_link')
                                            $icon = 'fa-question-circle';
                                    @endphp
                                    <i class="fas {{ $icon }} mr-3 w-5 text-center text-indigo-500"></i>
                                    <span>{{ $material->title }}</span>
                                    {{-- Nanti link ini bisa ngarah ke halaman materi kalo student udah enroll --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">Belum ada materi untuk course ini.</p>
                    @endif
                </div>

                {{-- Daftar Quiz --}}
                @if($isEnrolled || (Auth::check() && (Auth::user()->id == $course->mentor_id || Auth::user()->role == 'admin')))
                    <div class="bg-white p-6 rounded-xl shadow-xl">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-5">Daftar Quiz</h2>
                        @if($course->quizzes->isEmpty())
                            <p class="text-gray-600">Belum ada quiz untuk course ini.</p>
                        @else
                            <div class="space-y-4">
                                @foreach ($course->quizzes as $quiz)
                                    <div
                                        class="border p-4 rounded-lg hover:shadow-md transition-shadow flex justify-between items-center">
                                        <div>
                                            <h3 class="text-xl font-medium text-indigo-700">{{ $quiz->title }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($quiz->description, 100) }}</p>
                                            <div class="text-xs text-gray-500 mt-2">
                                                <span><i class="fas fa-question-circle mr-1"></i>
                                                    {{ $quiz->questions_count ?? $quiz->questions->count() }} Pertanyaan</span>
                                                @if($quiz->duration_minutes)
                                                    <span class="ml-3"><i class="fas fa-clock mr-1"></i> {{ $quiz->duration_minutes }}
                                                        Menit</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            @auth
                                                @if(Auth::user()->role == 'student' && $isEnrolled)
                                                    {{-- Cek apakah student sudah pernah mengerjakan atau ada attempt yang aktif --}}
                                                    @php
                                                        // $existingAttempt = Auth::user()->quizAttempts()->where('quiz_id', $quiz->id)->whereNull('submitted_at')->first();
                                                        // $completedAttempt = Auth::user()->quizAttempts()->where('quiz_id', $quiz->id)->whereNotNull('submitted_at')->orderBy('submitted_at', 'desc')->first();
                                                    @endphp
                                                    {{-- Logika untuk tombol "Lanjutkan Quiz" atau "Lihat Hasil" bisa ditambahkan di sini --}}
                                                    <a href="{{ route('student.quiz.attempt.start', ['course' => $course->slug, 'quiz' => $quiz->id]) }}"
                                                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:shadow-md transition-colors">
                                                        Kerjakan Quiz
                                                    </a>
                                                @elseif(Auth::user()->id == $course->mentor_id)
                                                    <a href="{{ route('mentor.courses.quizzes.questions.index', ['course' => $course->slug, 'quiz' => $quiz->id]) }}"
                                                        class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                                                        Kelola Pertanyaan
                                                    </a>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Jika perlu plugin Tailwind Typography, pastikan sudah diinstall dan di-setup --}}
    {{-- Kalo gak pake plugin, styling teks deskripsi mungkin perlu diatur manual --}}
    {{--
    <link rel="stylesheet" href="path/to/typography.css"> --}}
    <style>
        .prose img {
            /* Contoh styling buat gambar di dalem deskripsi */
            border-radius: 0.5rem;
        }

        .fa-2x {
            font-size: 1.75em;
        }
    </style>
@endpush

@push('scripts')
    {{-- Script JS tambahan jika ada --}}
@endpush