@extends('layouts.layout') {{-- GANTI 'layouts.app' dengan nama file layout utama lo kalo beda --}}

@section('title', 'Overview - LMS Siswa')

@push('styles')
<style>
    /* Custom style buat pie chart sederhana pake CSS (opsional) */
    /* Variabel --progress akan diisi dari data dinamis di tiap card course */
    .pie-chart-container { /* Tambahan container untuk tooltip atau info lain jika perlu */
        position: relative;
        width: 70px; /* Ukuran pie chart lebih kecil untuk card */
        height: 70px;
    }
    .pie-chart {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-image: conic-gradient(
            #4A55A2 var(--progress, 0%),  /* Warna progress (ungu tua, sesuaikan) */
            #E0E7FF var(--progress, 0%)   /* Warna sisa/background pie (ungu muda, sesuaikan) */
        );
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem; /* Ukuran font persentase di tengah */
        font-weight: bold;
        color: #e100ff; /* Warna teks progress */
    }
    .fa-2x { /* Sedikit penyesuaian ukuran ikon jika diperlukan */
        font-size: 1.6em; 
    }
</style>
@endpush

@section('content')
@include('components.header')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('layouts.partials.alerts') {{-- Nampilin notifikasi session --}}

    {{-- Baris Statistik Utama (Card) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-5 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center mb-2">
                <i class="fas fa-tasks fa-lg text-blue-600 mr-3"></i>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Courses In Progress</p>
            </div>
            <p class="text-3xl font-semibold text-gray-800">{{ $coursesInProgressCount ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center mb-2">
                <i class="fas fa-check-double fa-lg text-green-600 mr-3"></i>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Courses Completed</p>
            </div>
            <p class="text-3xl font-semibold text-gray-800">{{ $coursesCompletedCount ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center mb-2">
                <i class="far fa-clock fa-lg text-purple-600 mr-3"></i>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Hours Learning</p>
            </div>
            <p class="text-3xl font-semibold text-gray-800">{{ $hoursLearning ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center mb-2">
                <i class="fas fa-star fa-lg text-yellow-500 mr-3"></i>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Quiz Score</p>
            </div>
            <p class="text-3xl font-semibold text-gray-800">{{ $totalQuizScore ?? 0 }}</p>
        </div>
    </div>

    {{-- Bagian "MY COURSES" Preview --}}
    <div class="mb-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-700">My Courses</h2>
            <a href="{{ route('student.my-courses.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                Lihat Semua <i class="fas fa-angle-right ml-1"></i>
            </a>
        </div>
        @if($myRecentInProgressCourses && $myRecentInProgressCourses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($myRecentInProgressCourses as $course)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 ease-in-out">
                        <a href="{{ route('courses.show', $course->slug) }}" class="block">
                            @if($course->thumbnail_path)
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-300 flex items-center justify-center text-gray-500 rounded-t-xl">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif
                        </a>
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate" title="{{ $course->title }}">
                                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-indigo-600">{{ Str::limit($course->title, 45) }}</a>
                            </h3>
                            <p class="text-xs text-gray-500 mb-3">Oleh: {{ $course->mentor->name ?? 'N/A' }}</p>
                            
                            {{-- Pie Chart Progress (CSS Only) --}}
                            <div class="flex items-center justify-center my-3">
                                <div class="pie-chart-container">
                                    <div class="pie-chart" style="--progress: {{ $course->progress_percentage ?? 0 }}%;">
                                        <span clas>{{ $course->progress_percentage ?? 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-3">
                                <a href="{{ route('courses.show', $course->slug) }}" {{-- Nanti bisa ke halaman belajar langsung --}}
                                   class="w-full block text-center bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:shadow-md transition-colors">
                                    Lanjutkan Belajar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white text-center p-8 rounded-xl shadow-lg">
                <i class="fas fa-search-minus fa-3x text-gray-400 mb-4"></i>
                <p class="text-gray-600">Anda sedang tidak mengikuti course apapun saat ini.</p>
                <p class="text-gray-500 mt-2">Yuk, mulai petualangan belajar Anda!</p>
            </div>
        @endif
    </div>
</div>
@endsection