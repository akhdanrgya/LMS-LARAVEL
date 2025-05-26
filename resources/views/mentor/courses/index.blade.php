@extends('layouts.layout') {{-- GANTI 'layouts.app' dengan nama file layout utama lo kalo beda --}}

@section('title', 'Kelola Course Saya')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Course Saya</h1>
            <a href="{{ route('mentor.courses.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Course Baru</span>
            </a>
        </div>

        @include('layouts.partials.alerts') {{-- Nampilin notifikasi session (sukses/error) --}}

        @if($courses->isEmpty())
            <div class="bg-white text-center p-10 rounded-xl shadow-lg">
                <i class="fas fa-folder-open fa-3x text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Anda belum membuat course apapun.</p>
                <p class="text-gray-500 mt-2">Yuk, mulai buat course pertama Anda!</p>
                <a href="{{ route('mentor.courses.create') }}"
                    class="mt-6 inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow hover:shadow-md transition-all">
                    Buat Course Sekarang
                </a>
            </div>
        @else
            <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">Thumbnail</th>
                            <th class="py-3 px-6 text-left">Judul Course</th>
                            <th class="py-3 px-6 text-left">Slug</th>
                            <th class="py-3 px-6 text-center">Materi (Contoh)</th>
                            <th class="py-3 px-6 text-center">Tanggal Dibuat</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($courses as $course)
                            <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-150">
                                <td class="py-3 px-6 text-left">
                                    @if($course->thumbnail_path)
                                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}"
                                            class="w-20 h-12 object-cover rounded-md">
                                    @else
                                        <div
                                            class="w-20 h-12 bg-gray-300 flex items-center justify-center text-xs text-gray-500 rounded-md">
                                            No Image</div>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <span class="font-semibold">{{ $course->title }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $course->slug }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    {{-- Ini contoh, idealnya dari relasi atau count --}}
                                    {{ $course->materials_count ?? $course->materials->count() }}
                                </td>
                                <td class="py-3 px-6 text-center">
                                    {{ $course->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        {{-- INI DIA TOMBOL BARUNYA --}}
                                        <a href="{{ route('mentor.courses.materials.index', $course->slug) }}"
                                            class="w-8 h-8 rounded bg-blue-500 text-white flex items-center justify-center transform hover:scale-110 transition-transform duration-300"
                                            title="Kelola Materi & Quiz">
                                            <i class="fas fa-list-ul"></i>
                                        </a>
                                        {{-- Tombol Edit Course --}}
                                        <a href="{{ route('mentor.courses.edit', $course->slug) }}"
                                            class="w-8 h-8 rounded bg-yellow-500 text-white flex items-center justify-center transform hover:scale-110 transition-transform duration-300"
                                            title="Edit Course">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        {{-- Tombol Hapus Course --}}
                                        <form action="{{ route('mentor.courses.destroy', $course->slug) }}" method="POST"
                                            onsubmit="return confirm('Yakin mau hapus course \'{{ $course->title }}\'? Semua materi dan quiz di dalamnya juga akan terhapus!');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded bg-red-500 text-white flex items-center justify-center transform hover:scale-110 transition-transform duration-300"
                                                title="Hapus Course">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $courses->links() }} {{-- Link Pagination --}}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    {{-- Kalo ada style tambahan khusus buat halaman ini --}}
    <style>
        /* Styling tambahan jika diperlukan */
    </style>
@endpush