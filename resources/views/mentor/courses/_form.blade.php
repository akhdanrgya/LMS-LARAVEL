@csrf
<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Course</label>
    <input type="text" name="title" id="title" value="{{ old('title', $course->title ?? '') }}" 
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('title') border-red-500 @enderror" required>
    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="description" id="description" rows="5" 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('description') border-red-500 @enderror" required>{{ old('description', $course->description ?? '') }}</textarea>
    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-6">
    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Course (Opsional)</label>
    @if(isset($course) && $course->thumbnail_path)
        <div class="my-2">
            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-32 h-auto rounded">
        </div>
    @endif
    <input type="file" name="thumbnail" id="thumbnail" 
           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 ... @error('thumbnail') border-red-500 @enderror">
    @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>