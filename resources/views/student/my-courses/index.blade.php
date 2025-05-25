@extends('layouts.layout')

@section('title', 'Course Saya')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Course yang Saya Ikuti</h1>

    @include('layouts.partials.alerts')

    @if($enrolledCourses->isEmpty())
        <div class="bg-white text-center p-10 rounded-xl shadow-lg">
            <i class="fas fa-folder-open fa-3x text-gray-400 mb-4"></i>
            <p class="text-gray-600 text-lg">Anda belum mendaftar ke course manapun.</p>
            <p class="text-gray-500 mt-2">Yuk, cari course menarik untuk dipelajari!</p>
            <a href="{{ route('courses.index') }}" 
               class="mt-6 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow hover:shadow-md transition-all">
                Lihat Semua Course
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($enrolledCourses as $course)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform hover:scale-105 transition-transform duration-300">
                    @if($course->thumbnail_path)
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center text-gray-500">No Thumbnail</div>
                    @endif
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-600 mb-1">Oleh: {{ $course->mentor->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 mb-3">
                            Terdaftar pada: {{ $course->pivot->created_at ? $course->pivot->created_at->format('d M Y') : 'N/A' }}
                        </p>
                        {{-- Contoh progress bar sederhana (data progress perlu disiapkan) --}}
                        {{-- <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $course->pivot->completion_percentage ?? 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">{{ $course->pivot->completion_status == 'completed' ? 'Selesai' : 'Sedang Berlangsung' }}</p> --}}
                        
                        <div class="mt-auto">
                             {{-- Nanti link ini ngarah ke halaman belajar course tersebut --}}
                            <a href="{{ route('courses.show', $course->slug) }}" {{-- Atau route khusus student belajar --}}
                               class="w-full block text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:shadow-md transition-colors duration-150">
                                Mulai Belajar <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $enrolledCourses->links() }}
        </div>
    @endif
</div>
@endsection