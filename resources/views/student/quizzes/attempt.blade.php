@extends('layouts.layout') {{-- Sesuaikan layout utama lo --}}

@section('title', 'Mengerjakan Quiz: ' . $quiz->title)

@section('content')
@include('components.header')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 p-6 bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-indigo-700">{{ $quiz->title }}</h1>
                <p class="text-sm text-gray-600">Course: {{ $course->title }}</p>
            </div>
            @if($quiz->duration_minutes)
            <div class="text-right">
                <p class="text-sm text-gray-500">Waktu Pengerjaan:</p>
                <p class="text-xl font-semibold text-red-500" id="quiz-timer">
                    {{ $quiz->duration_minutes }}:00
                </p>
            </div>
            @endif
        </div>
        @if($quiz->description)
        <p class="mt-4 text-gray-700">{{ $quiz->description }}</p>
        @endif
    </div>

    @include('layouts.partials.alerts')

    <form action="{{ route('student.quiz.attempt.submit', ['course' => $course->slug, 'quiz' => $quiz->id, 'attempt' => $attempt->id]) }}" method="POST" id="quiz-form">
        @csrf
        
        @if($questions->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md shadow-md">
                <p class="font-bold">Oops!</p>
                <p>Belum ada pertanyaan untuk quiz ini.</p>
            </div>
        @else
            @foreach($questions as $index => $question)
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <div class="flex justify-between items-start mb-3">
                    <h2 class="text-lg font-semibold text-gray-800">Pertanyaan #{{ $loop->iteration }}</h2>
                    <span class="text-sm font-medium text-gray-600">Poin: {{ $question->points }}</span>
                </div>
                <div class="prose max-w-none mb-4">
                    {!! nl2br(e($question->question_text)) !!} {{-- Nampilin teks pertanyaan --}}
                </div>

                {{-- Area Jawaban --}}
                <div class="space-y-3">
                    @if($question->question_type === 'single_choice')
                        @foreach($question->answerOptions as $option)
                        <label class="flex items-center p-3 border rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $option->id }}" 
                                   class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-3 text-gray-700">{{ $option->option_text }}</span>
                        </label>
                        @endforeach
                    @elseif($question->question_type === 'multiple_choice')
                        @foreach($question->answerOptions as $option)
                        <label class="flex items-center p-3 border rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                            {{-- Nama input array buat multiple choice: answers[question_id][] --}}
                            <input type="checkbox" 
                                   name="answers[{{ $question->id }}][]" 
                                   value="{{ $option->id }}"
                                   class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-3 text-gray-700">{{ $option->option_text }}</span>
                        </label>
                        @endforeach
                    @elseif($question->question_type === 'essay')
                        <textarea name="answers[{{ $question->id }}]" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                  placeholder="Ketik jawaban esai Anda di sini..."></textarea>
                    @endif
                </div>
                 @error('answers.' . $question->id) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endforeach

            <div class="mt-8 text-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg text-lg transition-transform transform hover:scale-105">
                    Kumpulkan Jawaban Quiz
                </button>
            </div>
        @endif
    </form>
</div>

@if($quiz->duration_minutes)
@push('scripts')
<script>
    // Script Timer Sederhana
    const timerDisplay = document.getElementById('quiz-timer');
    const quizForm = document.getElementById('quiz-form');
    let timeLeft = {{ $quiz->duration_minutes * 60 }}; // Durasi dalam detik

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        seconds = seconds < 10 ? '0' + seconds : seconds; // Format detik jadi 00, 01, dst.
        
        timerDisplay.textContent = `${minutes}:${seconds}`;
        
        if (timeLeft > 0) {
            timeLeft--;
        } else {
            timerDisplay.textContent = "Waktu Habis!";
            timerDisplay.classList.add('text-red-700', 'font-bold');
            // Otomatis submit form kalo waktu habis
            // alert('Waktu habis! Quiz akan disubmit otomatis.');
            quizForm.submit(); 
        }
    }
    
    if(timerDisplay && quizForm) {
        const timerInterval = setInterval(updateTimer, 1000);
        // Hentikan interval jika form disubmit manual sebelum waktu habis
        quizForm.addEventListener('submit', function() {
            clearInterval(timerInterval);
        });
    }
</script>
@endpush
@endif

@endsection