{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')
    <h1>Welcome to Dashboard, {{ auth()->user()->name }}</h1>
@endsection
