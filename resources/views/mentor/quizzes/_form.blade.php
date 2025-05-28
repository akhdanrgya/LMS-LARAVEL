@extends('layouts.layout') {{-- Sesuaikan dengan layout utama lo --}}

@section('title', 'Tambah Quiz Baru untuk Course: ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <div>
            <a href="{{ route('mentor.courses.quizzes.index', $course->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-1 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Quiz untuk Course: {{ $course->title }}
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">Tambah Quiz ke: <span class="text-indigo-600">{{ $course->title }}</span></h1>
        </div>
    </div>

    @include('layouts.partials.alerts')

    <form action="{{ route('mentor.courses.quizzes.store', $course->slug) }}" method="POST" 
          class="bg-white p-6 md:p-8 rounded-xl shadow-xl max-w-lg mx-auto">
        
        @include('mentor.quizzes._form') {{-- Manggil partial form --}}

        <div class="mt-8 flex items-center justify-end space-x-4">
            <a href="{{ route('mentor.courses.quizzes.index', $course->slug) }}" class="text-gray-600 hover:text-gray-800">Batal</a>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-transform transform hover:scale-105">
                Simpan & Lanjut Tambah Pertanyaan
            </button>
        </div>
    </form>
</div>
@endsection