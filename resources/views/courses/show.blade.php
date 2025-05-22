@extends('layouts.layout')

@section('content')
<div class="min-h-screen pl-[198px]">
    <div class="">
        <!-- Cover Photo -->
        <div class="h-64 w-full relative">
            <img src="{{ $course->cover_photo ? asset('storage/' . $course->cover_photo) : asset('images/course-default.jpg') }}" 
                 alt="{{ $course->name }}" 
                 class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                <h1 class="text-3xl font-bold text-white">{{ $course->name }}</h1>
                <p class="text-gray-200 mt-2">by {{ $course->author->name }}</p>
            </div>
        </div>

        <!-- Course Details -->
        <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-semibold mb-4">About This Course</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($course->description)) !!}
                </div>

                <!-- Course Materials -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <i class="fas fa-list-ul"></i>
                        Course Content
                    </h3>
                    <div class="space-y-2">
                        @forelse ($course->materials as $material)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium">{{ $material->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ floor($material->duration_minutes / 60) }}h {{ $material->duration_minutes % 60 }}m
                                    </p>
                                </div>
                                @if($userEnrollment)
                                    <a href="{{ route('materials.show', [$course, $material]) }}" 
                                       class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-play-circle"></i> Start
                                    </a>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500">No materials available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <div class="bg-white p-4 rounded-lg">
                    <h3 class="font-semibold mb-3">Course Details</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-gray-500"></i>
                            <span>Duration: {{ $formattedDuration }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group text-gray-500"></i>
                            <span>Lessons: {{ $materialsCount }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user text-gray-500"></i>
                            <span>Instructor: {{ $course->author->name }}</span>
                        </div>
                    </div>

                    @if($userEnrollment)
                        <div class="mt-4 p-3 bg-green-50 text-green-700 rounded text-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            You're enrolled in this course
                        </div>
                        <a href="{{ route('materials.index', [$course, $course->materials->first()]) }}" 
                           class="mt-4 block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition">
                            Continue Learning
                        </a>
                    @else
                        <form action="{{ route('courses.enroll', $course) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                                Enroll Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection