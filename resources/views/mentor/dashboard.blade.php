@extends('layouts.layout') {{-- Sesuaikan dengan nama layout utama lo --}}

@section('title', 'Mentor Dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, Mentor {{ $mentorName }}!</h1>

    @include('layouts.partials.alerts') {{-- Nampilin notifikasi --}}

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-600 mr-4">
                    <i class="fas fa-book fa-2x"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Course Anda</p>
                    <p class="text-3xl font-semibold text-gray-800">{{ $totalCourses }}</p>
                </div>
            </div>
        </div>
        {{-- Tambahin card statistik lain kalo perlu --}}
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('mentor.courses.index') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out flex items-center space-x-2">
                <i class="fas fa-list-alt"></i>
                <span>Kelola Course Saya</span>
            </a>
            <a href="{{ route('mentor.courses.create') }}"
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Course Baru</span>
            </a>
        </div>
    </div>
</div>
@endsection