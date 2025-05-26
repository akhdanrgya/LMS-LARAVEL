@extends('layouts.layout')

@section('title', 'All Course')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Telusuri Semua Course</h1>
        {{-- Bisa tambahin filter atau search di sini nanti --}}
    </div>

    @include('layouts.partials.alerts')

    @if($courses->isEmpty())
        <div class="bg-white text-center p-10 rounded-xl shadow-lg">
            <i class="fas fa-search-minus fa-3x text-gray-400 mb-4"></i>
            <p class="text-gray-600 text-lg">Oops! Sepertinya belum ada course yang tersedia saat ini.</p>
            <p class="text-gray-500 mt-2">Silakan cek kembali nanti ya.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($courses as $course)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 ease-in-out flex flex-col">
                <a href="{{ route('courses.show', $course->slug) }}">
                    @if($course->thumbnail_path)
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center text-gray-500">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    @endif
                </a>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-indigo-600">{{ $course->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-chalkboard-teacher mr-1 text-gray-500"></i>
                        Mentor: {{ $course->mentor->name ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 mb-3 truncate">
                        {{ Str::limit(strip_tags($course->description), 100) }}
                    </p>
                    {{-- Nanti bisa tambahin info rating, jumlah student, dll. --}}
                    <div class="mt-auto pt-3 border-t border-gray-200">
                        <a href="{{ route('courses.show', $course->slug) }}" 
                           class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-300">
                            Lihat Detail
                        </a>
                        {{-- Tombol Enroll bisa ditambahkan di sini jika user adalah student dan belum enroll --}}
                        {{-- @auth
                            @if(Auth::user()->role == 'student' && !Auth::user()->enrollments()->where('course_id', $course->id)->exists())
                                <form action="{{ route('student.courses.enroll', $course->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg">
                                        Enroll Sekarang
                                    </button>
                                </form>
                            @endif
                        @endauth --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $courses->links() }} {{-- Pagination --}}
        </div>
    @endif
</div>
@endsection