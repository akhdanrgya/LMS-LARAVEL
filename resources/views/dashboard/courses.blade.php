@extends('layouts.layout')

@section('content')
    <div class="pl-[198px]">

        <div class="max-w-screen-xl mx-auto px-6 py-6 flex flex-col gap-8">

            <!-- header -->
            @include('components.header')

            <!-- search + filters -->
            <div class="flex justify-between items-center w-full h-10">
                <!-- search -->
                <div class="flex items-center w-[327px] gap-6">
                    <div class="flex items-center w-full gap-2 px-4 py-3 bg-white border border-[#d9d9d9] rounded-full">
                        <p class="text-base text-[#b3b3b3] flex-grow">Search</p>
                        <i class="fa-solid fa-magnifying-glass text-[#1e1e1e]"></i>
                    </div>
                </div>

                <!-- filters -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-2 p-2 bg-[#2c2c2c] rounded-lg">
                        <i class="fa-solid fa-sliders text-white text-sm"></i>
                        <p class="text-white text-sm">Filters</p>
                    </div>
                    <div class="flex items-center gap-2 p-2 border border-[#d9d9d9] rounded-lg">
                        <i class="fa-solid fa-calendar text-[#4c5a73] text-sm"></i>
                        <p class="text-[#4c5a73] text-sm">Date</p>
                    </div>
                    <div class="flex items-center gap-2 p-2 border border-[#d9d9d9] rounded-lg">
                        <i class="fa-solid fa-tag text-[#4c5a73] text-sm"></i>
                        <p class="text-[#4c5a73] text-sm">Tags</p>
                    </div>
                </div>
            </div>

            <!-- card container -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <x-coursesCard2 :course="$course" />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">You haven't enrolled in any courses yet.</p>
                        <a href="{{ route('courses.index') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                            Browse Courses
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection