@extends('layouts.layout')

@section('title', 'Quiz untuk Course: ' . $course->title)

@section('content')
@include('components.header')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <div>
                <a href="{{ route('mentor.courses.index') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Course Saya
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Quiz untuk: <span class="text-indigo-700">{{ $course->title }}</span>
                </h1>
            </div>
            <a href="{{ route('mentor.courses.quizzes.create', $course->slug) }}"
                class="mt-4 sm:mt-0 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-5 rounded-lg shadow-md">
                <i class="fas fa-plus-circle"></i> Tambah Quiz Baru
            </a>
        </div>

        @include('layouts.partials.alerts')

        @if($quizzes->isEmpty())
            <div class="bg-white text-center p-10 rounded-xl shadow-lg">
                <p class="text-gray-600 text-lg">Course ini belum memiliki quiz.</p>
                <a href="{{ route('mentor.courses.quizzes.create', $course->slug) }}"
                    class="mt-6 inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                    Buat Quiz Pertama
                </a>
            </div>
        @else
            <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-6 text-left">Judul Quiz</th>
                            <th class="py-3 px-6 text-left">Deskripsi</th>
                            <th class="py-3 px-6 text-center">Durasi (Menit)</th>
                            <th class="py-3 px-6 text-center">Jumlah Soal (Contoh)</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($quizzes as $quiz)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">{{ $quiz->title }}</td>
                                <td class="py-3 px-6 text-left">{{ Str::limit($quiz->description, 50) }}</td>
                                <td class="py-3 px-6 text-center">{{ $quiz->duration_minutes ?? '-' }}</td>
                                <td class="py-3 px-6 text-center">{{ $quiz->questions_count ?? $quiz->questions->count() }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        {{-- Di dalam loop @foreach ($quizzes as $quiz) di kolom Aksi --}}
                                        <a href="{{ route('mentor.courses.quizzes.questions.index', ['course' => $course->slug, 'quiz' => $quiz->id]) }}"
                                            class="w-8 h-8 rounded bg-blue-500 text-white flex items-center justify-center transform hover:scale-110"
                                            title="Kelola Pertanyaan Quiz Ini">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                        <a href="{{ route('mentor.courses.quizzes.edit', [$course->slug, $quiz->id]) }}"
                                            class="text-yellow-500 hover:text-yellow-700" title="Edit Quiz">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('mentor.courses.quizzes.destroy', [$course->slug, $quiz->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin hapus quiz ini? Semua pertanyaan di dalamnya juga akan hilang.');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus Quiz">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $quizzes->links() }}</div>
        @endif
    </div>
@endsection