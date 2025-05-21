@extends('layouts.layout')

@section('content')
    <div class="pl-[198px]">

        <div class="max-w-screen-xl mx-auto px-6 py-6 flex flex-col gap-8">

            <!-- header -->
            <div class="flex justify-between items-center w-full h-10">
                <p class="text-3xl text-[#4c5a73]">My Courses</p>
                <div class="flex items-center gap-6">
                    <i class="fa-regular fa-bell text-xl text-[#4c5a73]"></i>
                    <p class="text-xl text-[#4c5a73] text-center">{{Auth::user()->name}}</p>
                    <i class="fa-solid fa-chevron-down text-xl text-[#4c5a73]"></i>
                </div>
            </div>

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
            <div class="flex flex-col gap-6 w-full ">
                <!-- card 1 -->
                @include("components.coursesCard2")
            </div>
            
        </div>
    </div>
@endsection