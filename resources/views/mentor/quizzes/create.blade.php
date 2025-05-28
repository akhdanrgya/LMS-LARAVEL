@extends('layouts.layout')

@section('title', 'Tambah Quiz Baru untuk Course: ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Tambah Quiz ke: <span class="text-indigo-600">{{ $course->title }}</span></h1>
        <a href="{{ route('mentor.courses.quizzes.index', $course->slug) }}" class="text-indigo-600 hover:text-indigo-800">← Kembali ke Daftar Quiz</a>
    </div>

    <form action="{{ route('mentor.courses.quizzes.store', $course->slug) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Judul Quiz <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
            <textarea name="description" id="description" rows="4" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Durasi Pengerjaan (Menit, Opsional)</label>
            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" min="1"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('duration_minutes') border-red-500 @enderror">
            @error('duration_minutes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        {{-- Nanti bisa ada pilihan untuk mengaitkan quiz ke material tertentu jika perlu --}}
        {{-- <div class="mb-4">
            <label for="material_id" class="block text-sm font-medium text-gray-700">Kaitkan dengan Materi (Opsional)</label>
            <select name="material_id" id="material_id" class="mt-1 block w-full rounded-md ...">
                <option value="">-- Tidak terkait materi spesifik --</option>
                @foreach($course->materials as $material)
                    <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>{{ $material->title }}</option>
                @endforeach
            </select>
        </div> --}}

        <div class="mt-6">
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Simpan & Lanjut Tambah Pertanyaan
            </button>
        </div>
    </form>
</div>
@endsection