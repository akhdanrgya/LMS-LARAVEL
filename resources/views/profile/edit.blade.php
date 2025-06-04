@extends('layouts.layout') {{-- Sesuaikan dengan layout utama lo --}}

@section('title', 'Edit Profil Saya')

@push('styles')
<style>
    .profile-picture-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Edit Profil Saya</h1>

    @include('layouts.partials.alerts')

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-8 rounded-xl shadow-xl space-y-6">
        @csrf
        @method('PUT')

        {{-- FOTO PROFIL --}}
        <div class="flex flex-col items-center">
            @php
                $currentProfilePicture = null;
                if(Auth::user()->role === 'student' && Auth::user()->studentProfile && Auth::user()->studentProfile->profile_picture_path) {
                    $currentProfilePicture = Storage::url(Auth::user()->studentProfile->profile_picture_path);
                } elseif(Auth::user()->role === 'mentor' && Auth::user()->mentorProfile && Auth::user()->mentorProfile->profile_picture_path) {
                    $currentProfilePicture = Storage::url(Auth::user()->mentorProfile->profile_picture_path);
                }
            @endphp
            <img src="{{ $currentProfilePicture ?? asset('images/default_avatar.png') }}" alt="Foto Profil" id="profilePicturePreview" class="profile-picture-preview mb-3">
            <label for="profile_picture" class="cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm">
                Ganti Foto Profil
            </label>
            <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*" onchange="previewProfilePicture(event)">
            @error('profile_picture') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- DATA USER DASAR --}}
        <fieldset class="border p-4 rounded-md">
            <legend class="text-lg font-semibold px-2 text-gray-700">Informasi Akun</legend>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </fieldset>

        {{-- GANTI PASSWORD --}}
        <fieldset class="border p-4 rounded-md">
            <legend class="text-lg font-semibold px-2 text-gray-700">Ganti Password (Opsional)</legend>
            <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengganti password.</p>
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('current_password') border-red-500 @enderror">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="new_password" id="new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('new_password') border-red-500 @enderror">
                @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </fieldset>

        {{-- PROFIL SPESIFIK MENTOR --}}
        @if($user->role === 'mentor' && $user->mentorProfile)
        <fieldset class="border p-4 rounded-md">
            <legend class="text-lg font-semibold px-2 text-gray-700">Profil Mentor</legend>
            <div class="mb-4">
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio Singkat</label>
                <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('bio') border-red-500 @enderror">{{ old('bio', $user->mentorProfile->bio) }}</textarea>
                @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="expertise" class="block text-sm font-medium text-gray-700">Keahlian (pisahkan dengan koma)</label>
                <input type="text" name="expertise" id="expertise" value="{{ old('expertise', $user->mentorProfile->expertise) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('expertise') border-red-500 @enderror">
                @error('expertise') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
             <div class="mb-4">
                <label for="experience_years" class="block text-sm font-medium text-gray-700">Pengalaman (Tahun)</label>
                <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years', $user->mentorProfile->experience_years) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('experience_years') border-red-500 @enderror">
                @error('experience_years') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="linkedin_url" class="block text-sm font-medium text-gray-700">URL Profil LinkedIn</label>
                <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $user->mentorProfile->linkedin_url) }}" placeholder="https://linkedin.com/in/namaprofil" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('linkedin_url') border-red-500 @enderror">
                @error('linkedin_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="website_url" class="block text-sm font-medium text-gray-700">URL Website/Portofolio Pribadi</label>
                <input type="url" name="website_url" id="website_url" value="{{ old('website_url', $user->mentorProfile->website_url) }}" placeholder="https://websiteanda.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('website_url') border-red-500 @enderror">
                @error('website_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </fieldset>
        @endif

        {{-- PROFIL SPESIFIK STUDENT --}}
        @if($user->role === 'student' && $user->studentProfile)
        <fieldset class="border p-4 rounded-md">
            <legend class="text-lg font-semibold px-2 text-gray-700">Informasi Student</legend>
            <div class="mb-2">
                <p class="text-sm text-gray-600">Total Skor Anda: <span class="font-bold text-indigo-600">{{ $user->studentProfile->total_score ?? 0 }}</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Level Anda: <span class="font-bold text-green-600">Level {{ $user->studentProfile->level ?? 1 }}</span></p>
            </div>
            {{-- Foto profil student udah di atas --}}
        </fieldset>
        @endif

        <div class="pt-5">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-transform transform hover:scale-105">
                Update Profil
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewProfilePicture(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('profilePicturePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush

@endsection