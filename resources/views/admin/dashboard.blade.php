@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card Statistik Total Users --}}
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-600">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        {{-- Card Statistik Total Mentors --}}
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-600">
                    <i class="fas fa-chalkboard-teacher fa-2x"></i> {{-- Ganti ikon kalo perlu --}}
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Mentors</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalMentors }}</p>
                </div>
            </div>
        </div>

        {{-- Card Statistik Total Students --}}
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-600">
                    <i class="fas fa-user-graduate fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Students</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        {{-- Card Statistik Total Courses --}}
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-20 text-purple-600">
                    <i class="fas fa-book-open fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Courses</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalCourses }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Quick Links</h2>
        <div class="flex flex-wrap gap-4">
            {{-- Nanti link-link ini ngarah ke halaman manajemen masing-masing --}}
            <a href="{{-- route('admin.users.index') --}}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                Kelola Users
            </a>
            <a href="{{-- route('admin.courses.management.index') --}}" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded">
                Kelola Courses
            </a>
            {{-- Tambah link lain kalo perlu --}}
        </div>
    </div>

</div>
@endsection