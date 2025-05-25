@extends('layouts.layout') {{-- Sesuaikan dengan nama layout utama lo --}}

@section('title', 'Dashboard Student')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, {{ $studentName ?? Auth::user()->name }}!</h1>

    @include('layouts.partials.alerts')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-600 mr-4">
                    <i class="fas fa-graduation-cap fa-2x"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Course Diikuti</p>
                    <p class="text-3xl font-semibold text-gray-800">{{ $enrolledCoursesCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        {{-- Tambahin card statistik lain (misal: Poin, Level - kalo udah ada fiturnya) --}}
        {{-- 
        <div class="bg-white p-5 rounded-xl shadow-lg">
            <p class="text-sm font-medium text-gray-500 uppercase">Total Poin Anda</p>
            <p class="text-3xl font-semibold text-gray-800">{{ Auth::user()->studentProfile->total_score ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-lg">
            <p class="text-sm font-medium text-gray-500 uppercase">Level Anda</p>
            <p class="text-3xl font-semibold text-gray-800">{{ Auth::user()->studentProfile->level ?? 1 }}</p>
        </div>
        --}}
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Mau Belajar Apa Hari Ini?</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('courses.index') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out flex items-center space-x-2">
                <i class="fas fa-search"></i>
                <span>Cari Semua Course</span>
            </a>
            <a href="{{ route('student.my-courses.index') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out flex items-center space-x-2">
                <i class="fas fa-layer-group"></i>
                <span>Course Saya</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .fa-2x { font-size: 1.75em; }
</style>
@endpush