@extends('layouts.layout')

@section('title', 'Edit Course: ' . $course->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Course (Admin): <span class="font-normal">{{ $course->title }}</span></h1>

    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-xl max-w-3xl mx-auto">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Course</label>
            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" id="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="mentor_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Mentor</label>
            <select name="mentor_id" id="mentor_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('mentor_id') border-red-500 @enderror">
                <option value="">-- Pilih Mentor --</option>
                @foreach ($mentors as $mentor)
                    <option value="{{ $mentor->id }}" {{ old('mentor_id', $course->mentor_id) == $mentor->id ? 'selected' : '' }}>
                        {{ $mentor->name }} ({{ $mentor->email }})
                    </option>
                @endforeach
            </select>
            @error('mentor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        {{-- Jika ada field status
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Course</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                <option value="pending" {{ old('status', $course->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="published" {{ old('status', $course->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="rejected" {{ old('status', $course->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        --}}

        <div class="mb-6">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Course (Opsional)</label>
            @if($course->thumbnail_path)
                <div class="my-2">
                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-48 h-auto rounded-md border">
                    <p class="text-xs text-gray-500 mt-1">Thumbnail saat ini. Pilih file baru untuk mengganti.</p>
                </div>
            @endif
            <input type="file" name="thumbnail" id="thumbnail" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('thumbnail') border-red-500 @enderror">
            @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-transform transform hover:scale-105">
                Update Course
            </button>
        </div>
    </form>
</div>
@endsection