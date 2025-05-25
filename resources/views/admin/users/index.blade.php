@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out">
            + Tambah User Baru
        </a>
    </div>

    @include('layouts.partials.alerts') {{-- Asumsi lo punya partial buat nampilin alert session --}}

    <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Nama</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-center">Role</th>
                    <th class="py-3 px-6 text-center">Bergabung Sejak</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                @forelse ($users as $user)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $user->name }}</td>
                    <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                    <td class="py-3 px-6 text-center">
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full
                            @if($user->role == 'admin') bg-red-100 text-red-700 @endif
                            @if($user->role == 'mentor') bg-green-100 text-green-700 @endif
                            @if($user->role == 'student') bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">{{ $user->created_at->format('d M Y H:i') }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded bg-yellow-500 text-white flex items-center justify-center mr-2 transform hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            @if(Auth::id() !== $user->id) {{-- Jangan tampilkan tombol hapus untuk diri sendiri --}}
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus user {{ $user->name }}?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded bg-red-500 text-white flex items-center justify-center transform hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $users->links() }} {{-- Pagination --}}
    </div>
</div>
@endsection