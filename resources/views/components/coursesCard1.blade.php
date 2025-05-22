@props(['name', 'description', 'cover_photo', 'lesson_count' => 0, 'progress' => 0])

<div class="flex gap-6 p-6 bg-white rounded-lg mb-4 items-center">
    <img src="{{ $cover_photo ? asset('storage/' . $cover_photo) : asset('images/course.jpg') }}" 
         alt="Course Thumbnail" class="w-[147px] h-[145px] object-cover">

    <div class="flex flex-col gap-2">
        <h3 class="text-[40px] font-bold text-[#766bd8]">{{ $name }}</h3>
        <p class="text-base font-bold text-[#4c5a73]">{{ $description }}</p>
        <p class="text-xl font-bold text-black">{{ $lesson_count }} Lesson{{ $lesson_count > 1 ? 's' : '' }}</p>
        <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
            <div class="h-full bg-[#766bd8] rounded-full" style="width: {{ $progress }}%"></div>
        </div>
    </div>
</div>
