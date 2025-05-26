<!DOCTYPE html>
<html lang="id"> {{-- Ganti ke 'id' kalo bahasa utama Indonesia --}}

<head>
  <meta charset="UTF-8">
  <title>{{ $title ?? 'Dashboard LMS' }}</title>

  <script src="https://cdn.tailwindcss.com"></script>

  {{-- PASTIKAN INTEGRITY HASH INI LENGKAP DAN BENAR DARI CDN RESMINYA --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" 
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" {{-- Contoh integrity hash, GANTI DENGAN YANG BENAR --}}
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  
  {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}} {{-- Kalo pake Vite, ini lebih disarankan daripada CDN di production --}}
  @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">
  <div class="flex min-h-screen">
    {{-- Sidebar lo (yang fixed width w-[198px]) --}}
    @include('components.sidebar')

    {{-- Konten Utama --}}
    {{-- KASIH MARGIN KIRI SELEBAR SIDEBAR --}}
    <div class="flex-1 ml-[198px]"> 
      {{-- mx-auto mungkin gak perlu lagi di sini kalo kontennya mau full selebar sisa area --}}
      {{-- Kalo konten di dalem @yield mau ada max-width dan ditengahin, atur di dalem view-nya aja --}}
      <div class="p-4 md:p-6 lg:p-8"> {{-- Tambahin padding di sini buat jarak konten dari tepi --}}
        
        {{-- Notifikasi (kalo mau dipindahin ke sini dari contoh gue sebelumnya) --}}
        @include('layouts.partials.alerts') {{-- Pastiin path ini bener atau pindahin logic alert ke sini --}}

        @yield('content')
      </div>
    </div>
  </div>
  @stack('scripts')
</body>
</html>