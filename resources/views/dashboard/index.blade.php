@extends('layouts.layout')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    {{-- Main Content --}}
    <div class="flex-1 p-8">
        {{-- Top bar --}}
        <div class="flex justify-between items-center mb-8 gap-10">
            <div class="w-full">
                <div class="relative">
                    <input type="text" placeholder="Search your course...."
                        class="w-full rounded-full px-6 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <span class="absolute right-4 top-2.5 text-gray-400">
                        🔍
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-indigo-800"></div>
                <span class="text-gray-800 font-medium uppercase">{{ Auth::user()->name }}</span>
            </div>
        </div>

        {{-- Welcome --}}
        <h2 class="text-2xl font-semibold mb-4 uppercase">WELCOME BACK {{ Auth::user()->name }} 👋</h2>

        {{-- Announcements --}}
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Announcements</h3>
            <div class="bg-white rounded-xl shadow-md p-6 h-32">
                {{-- Content goes here --}}
            </div>
        </div>

        {{-- Your Lesson --}}
        <div>
            <h3 class="font-semibold mb-2">Your Lesson</h3>
            <div class="bg-white rounded-xl shadow-md p-6 h-32">
                {{-- Content goes here --}}
            </div>
        </div>
    </div>
</div>
@endsection
