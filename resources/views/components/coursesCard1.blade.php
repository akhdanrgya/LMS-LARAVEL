@props([
    'name',
    'description',
    'cover_photo',
    'lesson_count' => 0,
    'progress' => 0
])

<a href="{{ route('courses.show', $name) }}" class="block hover:shadow-lg transition-shadow duration-300">
    <div class="flex gap-6 p-6 bg-white rounded-lg mb-4 items-center hover:bg-gray-50">
        <img src="{{ $cover_photo ? asset('storage/' . $cover_photo) : asset('images/course.jpg') }}" 
             alt="Course Thumbnail" 
             class="w-[147px] h-[145px] object-cover rounded-lg">
        
        <div class="flex flex-col gap-2 flex-1">
            <h3 class="text-[40px] font-bold text-[#766bd8]">{{ $name }}</h3>
            <p class="text-base font-bold text-[#4c5a73] line-clamp-2">{{ $description }}</p>
            
            <div class="mt-2">
                <p class="text-xl font-bold text-black mb-1">
                    {{ $lesson_count }} Lesson{{ $lesson_count != 1 ? 's' : '' }}
                </p>
                
                <div class="w-full h-2 bg-gray-200 rounded-full">
                    <div class="h-full bg-[#766bd8] rounded-full" 
                         style="width: {{ $progress }}%"
                         title="{{ $progress }}% Complete"></div>
                </div>
                
                @if($progress > 0)
                    <p class="text-sm text-gray-500 mt-1">{{ $progress }}% Complete</p>
                @endif
            </div>
        </div>
    </div>
</a>