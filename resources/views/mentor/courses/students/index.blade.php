@extends('layouts.layout') {{-- Sesuaikan layout utama lo --}}

@section('title', 'Student Terdaftar di Course: ' . $course->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('mentor.courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-1 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Course Saya
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Student Terdaftar di: <span class="text-indigo-700">{{ $course->title }}</span>
        </h1>
    </div>

    @include('layouts.partials.alerts')

    @if($enrolledStudents->isEmpty())
        <div class="bg-white text-center p-10 rounded-xl shadow-lg">
            <i class="fas fa-users-slash fa-3x text-gray-400 mb-4"></i>
            <p class="text-gray-600 text-lg">Belum ada student yang terdaftar di course ini.</p>
        </div>
    @else
        <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">Nama Student</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-center">Tanggal Enroll</th>
                        <th class="py-3 px-6 text-center">Status Course</th>
                        <th class="py-3 px-6 text-center">Aksi (Contoh)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @foreach ($enrolledStudents as $student)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-150">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            {{-- Asumsi $student adalah objek User, jadi punya profile_picture_path (opsional) --}}
                            {{-- @if($student->studentProfile && $student->studentProfile->profile_picture_path)
                                <img src="{{ asset('storage/' . $student->studentProfile->profile_picture_path) }}" alt="{{ $student->name }}" class="w-8 h-8 rounded-full inline-block mr-2 object-cover">
                            @else
                                <span class="w-8 h-8 rounded-full bg-gray-300 inline-flex items-center justify-center mr-2 text-xs">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                            @endif --}}
                            <span class="font-medium">{{ $student->name }}</span>
                        </td>
                        <td class="py-3 px-6 text-left">{{ $student->email }}</td>
                        <td class="py-3 px-6 text-center">
                            {{-- Data dari pivot table 'enrollments' --}}
                            {{ \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                @if($student->pivot->completion_status == 'completed') bg-green-100 text-green-700 @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $student->pivot->completion_status)) }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                {{-- Tombol Aksi (Contoh: Lihat Progres, Kirim Pesan, Hapus dari Course) --}}
                                {{-- <a href="#" class="text-blue-500 hover:text-blue-700" title="Lihat Progres">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="#" class="text-purple-500 hover:text-purple-700" title="Kirim Pesan">
                                    <i class="fas fa-envelope"></i>
                                </a> --}}
                                {{-- Tombol hapus student dari course mungkin lebih cocok buat admin --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $enrolledStudents->links() }} {{-- Pagination --}}
        </div>
    @endif
</div>
@endsection