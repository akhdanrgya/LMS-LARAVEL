@extends('layouts.layout')

@section('content')
    <div class="pl-[198px]">
        <div class="max-w-screen-xl mx-auto px-6 py-6 flex flex-col gap-8">
            @include('components.header')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($courses as $course)
            <div class="bg-white p-4 rounded-2xl shadow-sm flex flex-col gap-4">
                <img src="{{ $course->cover_photo ? asset('storage/' . $course->cover_photo) : asset('images/course.jpg') }}"
                alt="{{ $course->name }}" class="rounded-xl w-full h-[150px] object-cover" />

                <div class="flex flex-col gap-1">
                    <p class="text-[#4c5a73] text-base font-semibold">{{ $course->name }}</p>
                    <p class="text-[#b3b3b3] text-sm">by {{ $course->author->name ?? 'Unknown Author' }}</p>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2 text-[#4c5a73] text-sm">
                        <i class="fa-solid fa-clock text-[#4c5a73]"></i>
                        <p>{{ $course->formatted_duration ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-[#4c5a73] text-sm">
                        <i class="fa-solid fa-layer-group text-[#4c5a73]"></i>
                        <p>{{ $course->materials_count }} {{ Str::plural('Lesson', $course->materials_count) }}</p>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2 text-[#facc15] text-sm">
                            <i class="fa-solid fa-star"></i>
                            <p>{{ number_format($course->average_rating, 1) ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('courses.show', $course) }}"
                        class="bg-[#2c2c2c] text-white text-sm px-4 py-2 rounded-lg hover:bg-[#3d3d3d] transition">
                        View Course
                    </a>
                </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No courses available yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection