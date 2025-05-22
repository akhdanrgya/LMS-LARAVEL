@extends('layouts.layout')

@section('content')
    <div class="pl-[198px]">
        <div class="max-w-screen-xl mx-auto px-6 py-6 flex flex-col gap-8">
            {{-- Search & Profile --}}
            
            @include('components.header')

            {{-- Greeting --}}
            <div class="flex flex-col justify-center items-start bg-[#d3cfff] w-full p-6 rounded-2xl h-[150px]">
                <h1 class="text-3xl font-bold text-black">Hi, {{ Auth::user()->name }}</h1>
                <p class="text-base text-black">Ready to start your day with some lesson?</p>
            </div>

            {{-- Overview --}}
            <div class="w-full">
                <h2 class="text-xl font-bold text-[#4c5a73] mb-4">Overview</h2>
                <div class="flex flex-wrap gap-6">
                    {{-- Card 1 --}}
                    <div class="flex gap-6 items-center flex-1 min-w-[280px] bg-[#766bd8] p-6 rounded-md">
                        <div class="h-20 w-20 flex items-center justify-center rounded-md bg-white/20 text-white text-4xl">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <div class="text-white">
                            <p class="text-xl font-black">8%</p>
                            <p class="text-xl font-black">Rate</p>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="flex gap-6 items-center flex-1 min-w-[280px] bg-[#cea0cd] p-6 rounded-md">
                        <div class="h-20 w-20 flex items-center justify-center rounded-md bg-white/20 text-white text-4xl">
                            <i class="fa-solid fa-check-to-slot"></i>
                        </div>
                        <div class="text-white">
                            <p class="text-xl font-black">20%</p>
                            <p class="text-xl font-black">Complete</p>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="flex gap-6 items-center flex-1 min-w-[280px] bg-[#9dc2d7] p-6 rounded-md">
                        <div class="h-20 w-20 flex items-center justify-center rounded-md bg-white/20 text-white text-4xl">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="text-white">
                            <p class="text-xl font-black">2</p>
                            <p class="text-xl font-black">Favorite</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- timeline --}}

            <div class="w-full">
                <h2 class="text-xl font-bold text-[#4c5a73] mb-4">Timeline</h2>
                {{-- ini nanti isinya ada task yang blm di kerjain --}}
            </div>
            <h2 class="text-xl font-bold text-[#4c5a73] mb-4">Your Courses</h2>
            @forelse($courses as $course)
            <x-coursesCard1 
                :name="$course->name" 
                :description="$course->description" 
                :cover_photo="$course->cover_photo"
                :lesson_count="$course->materials_count"
                :progress="optional($course->pivot)->completed ? 100 : 0" />
        @empty
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                <p>You haven't enrolled in any courses yet.</p>
            </div>
        @endforelse
        
        </div>
    </div>
    </div>
@endsection