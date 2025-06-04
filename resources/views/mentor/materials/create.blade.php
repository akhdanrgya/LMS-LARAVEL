@extends('layouts.layout') {{-- Sesuaikan dengan layout utama lo --}}

@section('title', 'Tambah Materi Baru untuk Course: ' . $course->title)

@push('scripts') {{-- Alpine.js buat interaktivitas form tipe konten --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
@include('components.header')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Materi ke Course: <span class="font-normal text-indigo-600">{{ $course->title }}</span></h1>
        <a href="{{ route('mentor.courses.materials.index', $course->slug) }}" class="text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Daftar Materi</a>
    </div>

    @include('layouts.partials.alerts')

    <form action="{{ route('mentor.courses.materials.store', $course->slug) }}" method="POST" enctype="multipart/form-data" 
          class="bg-white p-8 rounded-xl shadow-xl max-w-2xl mx-auto"
          x-data="{ contentType: '{{ old('content_type', 'text') }}' }">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Materi <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="content_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten <span class="text-red-500">*</span></label>
            <select name="content_type" id="content_type" x-model="contentType" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content_type') border-red-500 @enderror">
                <option value="text" {{ old('content_type') == 'text' ? 'selected' : '' }}>Teks / Artikel</option>
                <option value="video_link" {{ old('content_type') == 'video_link' ? 'selected' : '' }}>Link Video (YouTube, Vimeo, dll)</option>
                <option value="file_path" {{ old('content_type') == 'file_path' ? 'selected' : '' }}>Upload File (PDF, Doc, PPT, Gambar, Video Pendek)</option>
            </select>
            @error('content_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Konten Teks --}}
        <div class="mb-4" x-show="contentType === 'text'">
            <label for="content_text" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten Teks <span class="text-red-500">*</span></label>
            <textarea name="content_text" id="content_text" rows="10" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content_text') border-red-500 @enderror">{{ old('content_text') }}</textarea>
            @error('content_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-500 mt-1">Anda bisa menggunakan Markdown atau editor WYSIWYG di sini nanti.</p>
        </div>

        {{-- Input Link Video --}}
        <div class="mb-4" x-show="contentType === 'video_link'">
            <label for="content_video_link" class="block text-sm font-medium text-gray-700 mb-1">URL Video <span class="text-red-500">*</span></label>
            <input type="url" name="content_video_link" id="content_video_link" value="{{ old('content_video_link') }}" placeholder="https://www.youtube.com/watch?v=xxxx"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content_video_link') border-red-500 @enderror">
            @error('content_video_link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Upload File --}}
        <div class="mb-6" x-show="contentType === 'file_path'">
            <label for="content_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File <span class="text-red-500">*</span></label>
            <input type="file" name="content_file" id="content_file" 
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('content_file') border-red-500 @enderror">
            @error('content_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-500 mt-1">Max file: 20MB. Tipe: PDF, Doc, PPT, Gambar, Video pendek.</p>
        </div>

        <div class="mb-4">
            <label for="order_sequence" class="block text-sm font-medium text-gray-700 mb-1">Urutan Materi (Opsional)</label>
            <input type="number" name="order_sequence" id="order_sequence" value="{{ old('order_sequence') }}" min="0"
                   class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('order_sequence') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk urutan otomatis di akhir.</p>
            @error('order_sequence') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end space-x-4 mt-8">
            <a href="{{ route('mentor.courses.materials.index', $course->slug) }}" class="text-gray-600 hover:text-gray-800">Batal</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-transform transform hover:scale-105">
                Simpan Materi
            </button>
        </div>
    </form>
</div>
@endsection