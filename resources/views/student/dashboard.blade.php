@extends('layouts.layout') {{-- Sesuaikan dengan nama layout utama lo --}}

@section('title', 'Dashboard Student')

@section('content')
{{-- Div utama dari inspect element lo, dengan gradient dan shadow --}}
@include('components.header')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

  {{-- Bagian Atas: Search Bar & Info User --}}
  {{-- Ini mungkin bagian dari header global di layout utama lo. Kalo iya, bagian ini bisa dihapus dari sini. --}}
  {{-- Kalo ini emang bagian dari konten dashboard, biarin aja. --}}

  {{-- Salam Pembuka --}}
  <div class="flex flex-col justify-center items-start self-stretch flex-grow-0 flex-shrink-0 h-auto gap-[5px] px-8 py-6 rounded-[30px] my-5"
    style="background: linear-gradient(to bottom, #fff 0%, #d3cfff 53.37%); box-shadow: 0px 4px 4px 0 rgba(0,0,0,0.05);">
    <div class="flex flex-col justify-center items-start flex-grow-0 flex-shrink-0 relative">
      <p class="flex-grow-0 flex-shrink-0 text-[32px] sm:text-[40px] font-semibold text-left text-[#4c5a73]">Hi, {{ $student->name }}</p>
      <p class="flex-grow-0 flex-shrink-0 text-base text-left text-[#4c5a73]">
        Ready to start your day with some lesson?
      </p>
    </div>
  </div>

  {{-- Banner Ilustrasi & AE Status (All Status) --}}
  {{-- Ini bagian yang kompleks dengan SVG, gue coba masukin SVG yang lo kasih --}}
  <div class="flex justify-start items-center self-stretch flex-grow-0 flex-shrink-0 relative gap-[30px] mb-10">
    {{-- Banner Ilustrasi Utama --}}
  </div>

  <div class="flex flex-col justify-start items-start self-stretch flex-grow-0 flex-shrink-0 w-full relative gap-10 mb-4">
    <p class="self-stretch w-full text-xl sm:text-2xl font-light text-left text-[#333]">
      All Status
    </p>
    <div class="w-full p-10 rounded-[10px] bg-white grid grid-cols-1 sm:grid-cols-3 gap-4" style="box-shadow: 0px 0px 2px 0 rgba(0,0,0,0.25);">
        <div class="flex flex-col items-center justify-center">
            <i class="fas fa-book-open fa-2x text-[#2D9CDB] mr-3 text-center"></i>
            <div>
                <p class="text-sm sm:text-base font-light text-center text-[#1c1b1f]">{{ $enrolledCoursesCount }} Courses</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center">
             <i class="fas fa-file-signature fa-2x text-[#2D9CDB] mr-3 text-center"></i>
            <div>
                <p class="text-sm sm:text-base font-light text-center text-[#1c1b1f]">{{ $completedQuizzesCount }}/{{ $totalQuizzesInEnrolledCourses }} Quizzes</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center">
            <i class="fas fa-hourglass-half fa-2x text-[#2D9CDB] mr-3 text-center"></i>
            <div>
                <p class="text-sm sm:text-base font-light text-center text-[#1c1b1f]">{{ $learningHours }}</p>
            </div>
        </div>
    </div>
  </div>

  {{-- TIMELINE --}}
  <div class="flex flex-col justify-start items-start self-stretch flex-grow-0 flex-shrink-0 w-full relative gap-10 mb-4">
      <p class="self-stretch w-full text-xl text-left text-[#4c5a73] mt-4">TIMELINE</p>
      <div class="w-full space-y-4">
    @forelse($timelineQuizzes as $quizItem)
    <div class="flex justify-between items-center w-full relative gap-4 p-4 sm:p-6 rounded-[30px] bg-white shadow-md">
        <div class="flex items-center">
            {{-- Ganti dengan ikon atau gambar yang sesuai --}}
            <div class="w-[70px] h-[70px] sm:w-[100px] sm:h-[100px] bg-red-100 rounded-2xl flex items-center justify-center mr-4">
                <i class="fas fa-file-alt fa-2x text-red-500"></i>
            </div>
            <div class="flex flex-col justify-center items-start relative gap-1">
                <p class="text-lg sm:text-2xl md:text-4xl font-bold text-left text-black">{{ Str::limit($quizItem->title, 25) }}</p>
                <p class="text-xs sm:text-base font-semibold text-left text-[#4c5a73]">
                    {{ $quizItem->course->title ?? 'Unknown Course' }} - QUIZ OPENED<br>
                    {{-- DUE : {{ $quizItem->due_date ? $quizItem->due_date->format('d/m/Y H:i') : 'Kapan saja' }} --}}
                    DUE : Kapan saja (Contoh)
                </p>
            </div>
        </div>
        @php
            // Cek apakah quiz ini sudah dikerjakan oleh student yang login
            // $attempt = Auth::user()->quizAttempts()->where('quiz_id', $quizItem->id)->whereNotNull('submitted_at')->first();
        @endphp
        {{-- @if($attempt) --}}
            {{-- <a href="{{ route('student.quiz.attempt.result', $attempt->id) }}" class="flex justify-center items-center relative gap-2.5 p-2.5 rounded-[20px] bg-green-500 text-white text-xs sm:text-base font-bold text-center whitespace-nowrap">
                Lihat Hasil
            </a> --}}
            {{-- @else --}}
            <a href="{{ route('student.quiz.attempt.start', ['course' => $quizItem->course->slug, 'quiz' => $quizItem->id]) }}" class="flex justify-center items-center relative gap-2.5 p-2.5 rounded-[20px] bg-[#4c5a73] text-white text-xs sm:text-base font-bold text-center whitespace-nowrap">
                Attempt quiz now
            </a>
        {{-- @endif --}}
    </div>
    @empty
    <div class="bg-white p-6 rounded-[30px] text-center">
        <p class="text-gray-600">Timeline Anda kosong untuk saat ini.</p>
    </div>
    @endforelse
    {{-- Contoh TUGAS (Fitur Baru, data statis) --}}
    {{-- <div class="flex justify-between items-center w-full relative gap-4 p-4 sm:p-6 rounded-[30px] bg-white shadow-md opacity-60">
        <div class="flex items-center">
            <div class="w-[70px] h-[70px] sm:w-[100px] sm:h-[100px] bg-blue-100 rounded-2xl flex items-center justify-center mr-4">
                <i class="fas fa-clipboard-list fa-2x text-blue-500"></i>
            </div>
            <div class="flex flex-col justify-center items-start relative gap-1">
                <p class="text-lg sm:text-2xl md:text-4xl font-bold text-left text-black">TUGAS WEEK 12 (Contoh)</p>
                <p class="text-xs sm:text-base font-semibold text-left text-[#4c5a73]">
                    SISTEM OPERASI - TUGAS OPENED<br>
                    DUE : 5/22/2025 23:59
                </p>
            </div>
        </div>
        <div class="flex justify-center items-center relative gap-2.5 p-2.5 rounded-[20px] bg-[#4c5a73] text-white text-xs sm:text-base font-bold text-center whitespace-nowrap">
            Add Submission
        </div>
    </div> --}}
</div>
</div>

{{-- ENROLL COURSES --}}
<div class="flex flex-col justify-start items-start self-stretch flex-grow-0 flex-shrink-0 w-full relative gap-10 mb-4">
    <p class="self-stretch w-full text-xl text-left text-[#4c5a73] mt-6">ENROLL COURSES</p>
    <div class="flex flex-col justify-start items-start self-stretch w-full relative gap-[1px] {{-- Ganti gap dari 34px ke 1px atau sesuai kebutuhan --}}">
        @forelse ($myEnrolledCourses as $enrolledCourse)
        <div class="self-stretch flex-grow-0 flex-shrink-0 h-auto sm:h-20 p-4 flex items-center justify-between relative bg-white border-b-[0.5px] border-[#e0e0e0]">
            <div class="flex items-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-[#e5d1ff] rounded-lg flex items-center justify-center mr-4">
                    {{-- Ganti dengan gambar course atau ikon default --}}
                <i class="fas fa-book-open-reader text-2xl text-[#4c5a73]"></i>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-medium text-left text-[#4f4f4f] hover:text-indigo-600">
                    <a href="{{ route('courses.show', $enrolledCourse->slug) }}">{{ $enrolledCourse->title }}</a>
                </h3>
                {{-- Progress bar dari inspect element lo --}}
                {{-- <div class="w-[161px] h-1 mt-1">
                    <div class="w-full h-full rounded-[50px] bg-[#E2EAF1] relative">
                        <div class="h-full rounded-[50px] bg-[#186DBF]" style="width: 20%;"></div> {{-- Ganti 20% dengan data progress --}}
                        {{-- </div>
                            <p class="text-xs font-light text-left text-[#383838] mt-0.5">Progress 20%</p>
                        </div> --}}
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-4 text-lg text-gray-500">
                    {{-- Ikon-ikon aksi dari inspect element lo (contoh) --}}
                    <a href="{{ route('courses.show', $enrolledCourse->slug) }}#materials" title="Lihat Materi"><i class="fas fa-book-open hover:text-indigo-600"></i></a>
                    {{-- <a href="#" title="Sertifikat"><i class="fas fa-certificate hover:text-indigo-600"></i></a> --}}
                </div>
            </div>
            @empty
            <div class="bg-white p-6 rounded-lg text-center">
                <p class="text-gray-600">Anda belum terdaftar di course manapun.</p>
                <a href="{{ route('courses.index') }}" class="text-indigo-600 hover:underline mt-2 inline-block">Jelajahi Semua Course &rarr;</a>
    </div>
    @endforelse
</div>
</div>

  {{-- COURSES (General Listing) --}}
  <div class="flex flex-col justify-start items-start self-stretch flex-grow-0 flex-shrink-0 w-full relative gap-10 mb-4">
      <p class="self-stretch w-full text-xl text-left text-[#4c5a73] mt-6">COURSES</p>
      <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          @forelse ($allAvailableCourses as $course)
          <div class="flex flex-col justify-center items-center w-full h-auto rounded-[30px] bg-white" style="box-shadow: 0px 4px 11px 0 rgba(0,0,0,0.25);">
            {{-- Bagian gambar dengan gradient dari inspect element lo --}}
            <div class="flex flex-col justify-start items-start self-stretch h-[187px] relative p-6 rounded-t-[30px]"
        style="">
        <a href="{{ route('courses.show', $course->slug) }}">
            @if($course->thumbnail_path)
            {{-- Idealnya gambar thumbnail, tapi karena ada SVG Python, kita coba tampilkan itu.
                Ini akan jadi kompleks jika SVG harus dinamis per course.
                Untuk sekarang, gue pake placeholder atau SVG Python statis jika ada.
                SVG Python yang lo kasih itu sangat besar, lebih baik jadi image atau komponen terpisah.
                Kita asumsikan ada $course->logo_svg atau $course->thumbnail_url.
                --}}
                <img src="{{ $course->cover_photo ?? asset('images/default_course_thumb.png') }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                {{-- Jika mau pake SVG Python dari inspect element lo, itu perlu ditaruh di sini --}}
                @else
                     <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <i class="fab fa-python fa-4x text-gray-500"></i> {{-- Contoh ikon Python --}}
                    </div>
                    @endif
            </a>
            <div class="absolute top-6 left-6">
                <p class="text-[28px] sm:text-[35px] font-bold text-left text-black">{{ $course->title }}</p>
                <p class="text-xs sm:text-sm text-left text-black">{{ $course->subtitle ?? 'Beginner to Advanced' }}</p> {{-- Asumsi ada subtitle --}}
            </div>
        </div>
        <div class="flex flex-col justify-start items-start self-stretch flex-grow gap-2.5 p-6">
            <p class="text-sm sm:text-lg font-light text-left text-black">{{ $course->materials_count ?? 'N/A' }} Chapter</p>
            <p class="text-sm sm:text-lg font-light text-left text-black">{{ $course->quizzes_count ?? 'N/A' }} Quiz</p>
            <p class="text-sm sm:text-lg font-light text-left text-black">Mentor: {{ $course->mentor->name ?? 'N/A' }}</p>
            {{-- Progress bar dari inspect element lo --}}
            {{-- <div class="self-stretch h-2 mt-2">
                <div class="w-full h-full rounded-[50px] bg-[#d1adff] relative">
                    <div class="h-full rounded-[50px] bg-[#9747ff]" style="width: 60%;"></div> {{-- Ganti 60% dengan data progress --}}
                {{-- </div>
                </div> --}}
            </div>
            <div class="flex flex-col justify-center items-center self-stretch p-4 sm:p-6 rounded-b-[30px] {{-- Dulu bg-[#d9d9d9] --}} border-t border-gray-200">
                <a href="{{ route('courses.show', $course->slug) }}" class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-3 rounded-md text-sm sm:text-lg">
                    View Course
                </a>
            </div>
        </div>
    @empty
    <p class="col-span-full text-center text-gray-600">Tidak ada course yang tersedia.</p>
    @endforelse
  </div>
</div>
</div>
@endsection