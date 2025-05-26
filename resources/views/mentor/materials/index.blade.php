@extends('layouts.layout') {{-- Sesuaikan dengan nama file layout utama lo --}}

@section('title', 'Materi untuk Course: ' . $course->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <div>
            <a href="{{ route('mentor.courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Course Saya
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Materi untuk: <span class="text-indigo-700">{{ $course->title }}</span>
            </h1>
        </div>
        <a href="{{ route('mentor.courses.materials.create', $course->slug) }}" 
           class="mt-4 sm:mt-0 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out flex items-center space-x-2">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Materi Baru</span>
        </a>
    </div>

    @include('layouts.partials.alerts') {{-- Nampilin notifikasi session --}}

    @if($materials->isEmpty())
        <div class="bg-white text-center p-10 rounded-xl shadow-lg">
            <i class="fas fa-box-open fa-3x text-gray-400 mb-4"></i>
            <p class="text-gray-600 text-lg">Course ini belum memiliki materi apapun.</p>
            <p class="text-gray-500 mt-2">Yuk, mulai tambahkan materi pertama Anda!</p>
            <a href="{{ route('mentor.courses.materials.create', $course->slug) }}" 
               class="mt-6 inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow hover:shadow-md transition-all">
                Tambah Materi Sekarang
            </a>
        </div>
    @else
        <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">Urutan</th>
                        <th class="py-3 px-6 text-left">Judul Materi</th>
                        <th class="py-3 px-6 text-left">Tipe Konten</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @foreach ($materials as $material)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-150">
                        <td class="py-3 px-6 text-left">
                            <span class="font-semibold">{{ $material->order_sequence }}</span>
                        </td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            {{ $material->title }}
                        </td>
                        <td class="py-3 px-6 text-left">
                            @if($material->content_type == 'text')
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">Teks/Artikel</span>
                            @elseif($material->content_type == 'video_link')
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full">Link Video</span>
                            @elseif($material->content_type == 'file_path')
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">File</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">{{ ucfirst(str_replace('_', ' ', $material->content_type)) }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                {{-- Tombol Lihat/Preview Materi (Placeholder) --}}
                                {{-- <a href="#" class="w-8 h-8 rounded bg-sky-500 text-white flex items-center justify-center transform hover:scale-110" title="Lihat Materi">
                                    <i class="fas fa-eye"></i>
                                </a> --}}
                                <a href="{{ route('mentor.courses.materials.edit', ['course' => $course->slug, 'material' => $material->id]) }}" 
                                   class="w-8 h-8 rounded bg-yellow-500 text-white flex items-center justify-center transform hover:scale-110" title="Edit Materi">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('mentor.courses.materials.destroy', ['course' => $course->slug, 'material' => $material->id]) }}" method="POST" onsubmit="return confirm('Yakin mau hapus materi \'{{ $material->title }}\'?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded bg-red-500 text-white flex items-center justify-center transform hover:scale-110" title="Hapus Materi">
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
            {{ $materials->links() }} {{-- Link Pagination --}}
        </div>
    @endif
</div>
@endsection

@push('styles')
{{-- Style tambahan jika diperlukan --}}
@endpush