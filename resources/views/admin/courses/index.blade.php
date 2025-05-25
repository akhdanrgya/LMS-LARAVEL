@extends('layouts.layout') {{-- Sesuaikan dengan layout utama lo --}}

@section('title', 'Kelola Semua Course')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4 sm:mb-0">Kelola Semua Course</h1>
        {{-- Form Search --}}
        <form action="{{ route('admin.courses.index') }}" method="GET" class="flex items-center">
            <input type="text" name="search" placeholder="Cari judul course/mentor..." 
                   value="{{ request('search') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    @include('layouts.partials.alerts')

    <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Judul Course</th>
                    <th class="py-3 px-6 text-left">Mentor</th>
                    <th class="py-3 px-6 text-center">Jumlah Materi (Contoh)</th>
                    <th class="py-3 px-6 text-center">Jumlah Student (Contoh)</th>
                    {{-- <th class="py-3 px-6 text-center">Status</th> --}}
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                @forelse ($courses as $course)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="font-medium">{{ $course->title }}</div>
                        <div class="text-xs text-gray-500">{{ $course->slug }}</div>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $course->mentor->name ?? 'N/A' }}</td>
                    <td class="py-3 px-6 text-center">{{ $course->materials_count ?? $course->materials->count() }}</td> {{-- Asumsi ada materials_count atau relasi materials --}}
                    <td class="py-3 px-6 text-center">{{ $course->students_count ?? $course->students->count() }}</td> {{-- Asumsi ada students_count atau relasi students --}}
                    {{-- <td class="py-3 px-6 text-center">{{ ucfirst($course->status ?? 'N/A') }}</td> --}}
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            {{-- Kalo mau ada tombol ganti status --}}
                            {{-- <form action="{{ route('admin.courses.manage.toggleStatus', [$course->id, 'published']) }}" method="POST" class="inline-block mr-1"> @csrf @method('PATCH') <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded">Publish</button></form> --}}
                            {{-- <form action="{{ route('admin.courses.manage.toggleStatus', [$course->id, 'pending']) }}" method="POST" class="inline-block mr-2"> @csrf @method('PATCH') <button type="submit" class="text-xs bg-yellow-500 text-white px-2 py-1 rounded">Pending</button></form> --}}
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="w-8 h-8 rounded bg-yellow-500 text-white flex items-center justify-center mr-2 transform hover:scale-110 transition-transform duration-300" title="Edit Course">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus course \'{{ $course->title }}\' secara permanen? Semua data terkait akan hilang.');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded bg-red-500 text-white flex items-center justify-center transform hover:scale-110 transition-transform duration-300" title="Hapus Course">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada data course.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $courses->appends(request()->query())->links() }} {{-- Pagination + bawa parameter search --}}
    </div>
</div>
@endsection