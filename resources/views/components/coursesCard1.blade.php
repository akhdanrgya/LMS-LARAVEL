<div class="flex gap-6 p-6 bg-white rounded-lg mb-4 items-center">
    <img src="{{ $courses->cover_photo ? asset('storage/' . $courses->cover_photo) : asset('images/course.jpg') }}" 
         alt="courses Thumbnail" class="w-[147px] h-[145px] object-cover">

    <div class="flex flex-col gap-2">
        <h3 class="text-[40px] font-bold text-[#766bd8]">{{ $courses->name }}</h3>
        <p class="text-base font-bold text-[#4c5a73]">{{ $courses->description }}</p>
        <p class="text-xl font-bold text-black">{{ $materialsCount ?? 0 }} Lesson{{ ($materialsCount ?? 0) > 1 ? 's' : '' }}</p>
    </div>
</div>