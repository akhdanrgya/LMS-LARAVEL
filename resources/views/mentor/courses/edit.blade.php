@extends('layouts.layout') {{-- Sesuaikan dengan nama layout utama lo --}}

@section('title', 'Create Courses')

@section('content')
@include('components.header')
    {{-- ... @extends, @section ... --}}
    <div>
        <form action="{{ route('mentor.courses.update', $course->slug) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('mentor.courses._form', ['course' => $course]) {{-- kirim $course ke partial --}}
            <button type="submit">Update Course</button>
        </form>
    </div>
    {{-- ... @endsection ... --}}
@endsection