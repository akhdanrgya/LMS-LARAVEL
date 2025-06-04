@extends('layouts.layout')

@section('title', $material->title . ' - ' . $course->title)

@section('content')
@include('components.header')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('courses.show', $course->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-1 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Course: {{ $course->title }}
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 break-words">
            Materi: <span class="text-indigo-700">{{ $material->title }}</span>
        </h1>
        <p class="text-sm text-gray-500 mt-1">Tipe: {{ ucfirst(str_replace('_', ' ', $material->content_type)) }}</p>
    </div>

    @include('layouts.partials.alerts')

    <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl">
        @if($material->content_type === 'text')
            <div class="prose max-w-none">
                {!! $material->content !!} {{-- Hati-hati pake {!! !!}, pastikan konten teks aman dari XSS. Kalo dari Markdown, perlu di-parse dulu. --}}
                {{-- Alternatif lebih aman: <div class="whitespace-pre-wrap">{{ $material->content }}</div> --}}
            </div>

        @elseif($material->content_type === 'video_link')
            @php
                // Coba deteksi link YouTube atau Vimeo untuk embed
                $embedUrl = null;
                if (Str::contains($material->content, 'youtube.com/watch?v=')) {
                    $videoId = Str::after($material->content, 'youtube.com/watch?v=');
                    $videoId = Str::before($videoId, '&'); // Hapus parameter tambahan
                    $embedUrl = "https://www.youtube.com/embed/" . $videoId;
                } elseif (Str::contains($material->content, 'youtu.be/')) {
                    $videoId = Str::after($material->content, 'youtu.be/');
                    $videoId = Str::before($videoId, '?');
                    $embedUrl = "https://www.youtube.com/embed/" . $videoId;
                } elseif (Str::contains($material->content, 'vimeo.com/')) {
                    $videoId = Str::after($material->content, 'vimeo.com/');
                    $embedUrl = "https://player.vimeo.com/video/" . $videoId;
                }
            @endphp
            @if($embedUrl)
                <div class="aspect-w-16 aspect-h-9"> {{-- Tailwind aspect ratio buat video responsif --}}
                    <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg shadow-md"></iframe>
                </div>
            @else
                <p class="text-gray-700">Link video tidak valid atau tidak didukung untuk embed. Anda bisa mengunjungi link: 
                    <a href="{{ $material->content }}" target="_blank" class="text-indigo-600 hover:underline">{{ $material->content }}</a>
                </p>
            @endif

        @elseif($material->content_type === 'file_path')
            <div class="text-center">
                <p class="text-gray-700 mb-4">File materi siap untuk diunduh:</p>
                <a href="{{ Storage::url($material->content) }}" target="_blank" 
                   class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-md inline-flex items-center">
                    <i class="fas fa-download mr-2"></i> Unduh File ({{ Str::afterLast($material->content, '/') }})
                </a>
                <p class="text-xs text-gray-500 mt-2">Path file: {{ $material->content }}</p>
            </div>
        @else
            <p class="text-gray-700">Tipe konten tidak dikenali.</p>
        @endif
    </div>

    {{-- Navigasi ke Materi Berikutnya/Sebelumnya (Opsional, bisa jadi pengembangan) --}}
    <div class="mt-8 flex justify-between">
        {{-- Logic buat $prevMaterial dan $nextMaterial perlu disiapin di controller --}}
        {{-- @if($prevMaterial)
            <a href="{{ route('student.courses.materials.show', [$course->slug, $prevMaterial->id]) }}" class="text-indigo-600 hover:text-indigo-800">&laquo; Materi Sebelumnya</a>
        @else
            <span>&nbsp;</span> 
        @endif
        @if($nextMaterial)
            <a href="{{ route('student.courses.materials.show', [$course->slug, $nextMaterial->id]) }}" class="text-indigo-600 hover:text-indigo-800">Materi Berikutnya &raquo;</a>
        @else
            <span>&nbsp;</span>
        @endif --}}
    </div>
</div>
@endsection