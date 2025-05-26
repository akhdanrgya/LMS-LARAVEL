@extends('layouts.layout') {{-- Sesuaikan dengan nama layout utama lo --}}

@section('title', 'Create Courses')

@section('content')
    {{-- ... @extends, @section ... --}}
    <div>
        <form action="{{ route('mentor.courses.store') }}" method="POST" enctype="multipart/form-data">
            @include('mentor.courses._form')
            <button type="submit">Simpan Course</button>
        </form>
    </div>
    {{-- ... @endsection ... --}}
@endsection