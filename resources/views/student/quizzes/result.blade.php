@extends('layouts.layout')

@section('title', 'Hasil Quiz: ' . $attempt->quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('courses.show', $attempt->quiz->course->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-1 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Course: {{ $attempt->quiz->course->title }}
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Hasil Quiz: <span class="text-indigo-700">{{ $attempt->quiz->title }}</span>
        </h1>
    </div>

    @include('layouts.partials.alerts')

    <div class="bg-white p-6 rounded-xl shadow-xl mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Ringkasan Pengerjaan</h2>
        <p class="text-gray-600">Tanggal Dikerjakan: <span class="font-medium">{{ $attempt->submitted_at->format('d M Y, H:i') }}</span></p>
        <p class="text-gray-600">Skor Anda: 
            <span class="text-2xl font-bold 
                @if($attempt->score >= ($attempt->quiz->questions->sum('points') * 0.75)) text-green-600 
                @elseif($attempt->score >= ($attempt->quiz->questions->sum('points') * 0.5)) text-yellow-600 
                @else text-red-600 
                @endif">
                {{ $attempt->score }}
            </span> 
            / {{ $attempt->quiz->questions->sum('points') }} Poin
        </p>
        {{-- Bisa tambahin info lain kayak status lulus/tidak, dll. --}}
    </div>

    <div class="bg-white p-6 rounded-xl shadow-xl">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Detail Jawaban:</h2>
        @if($attempt->quiz->questions->isEmpty())
            <p class="text-gray-600">Tidak ada pertanyaan di quiz ini.</p>
        @else
            @php
                // Buat mapping jawaban student biar gampang dicari
                $studentAnswersMap = $attempt->answers->keyBy('question_id');
            @endphp
            <div class="space-y-6">
                @foreach ($attempt->quiz->questions as $index => $question)
                <div class="border rounded-lg p-4 
                    @if(isset($studentAnswersMap[$question->id]) && $studentAnswersMap[$question->id]->is_correct === true) border-green-300 bg-green-50 
                    @elseif(isset($studentAnswersMap[$question->id]) && $studentAnswersMap[$question->id]->is_correct === false) border-red-300 bg-red-50 
                    @else border-gray-300 bg-gray-50 
                    @endif">
                    
                    <p class="text-sm text-gray-500">Pertanyaan #{{ $loop->iteration }} (Poin: {{ $question->points }})</p>
                    <p class="text-md font-medium text-gray-800 mt-1 mb-3">{!! nl2br(e($question->question_text)) !!}</p>

                    @php
                        $studentAnswer = $studentAnswersMap->get($question->id);
                    @endphp

                    @if($question->question_type === 'single_choice' || $question->question_type === 'multiple_choice')
                        <p class="text-xs font-semibold text-gray-600 mb-1">Pilihan Jawaban:</p>
                        <ul class="space-y-1 pl-1">
                            @foreach($question->answerOptions as $option)
                                <li class="text-sm flex items-center
                                    @if($option->is_correct) text-green-700 font-semibold @else text-gray-700 @endif">
                                    
                                    @if($studentAnswer)
                                        @if($question->question_type === 'single_choice' && $studentAnswer->answer_option_id == $option->id)
                                            <i class="fas {{ $option->is_correct ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} mr-2"></i>
                                        @elseif($question->question_type === 'multiple_choice' && in_array($option->id, json_decode($studentAnswer->answer_text ?? '[]') ?: []))
                                            <i class="fas {{ $option->is_correct ? 'fa-check-square text-green-500' : 'fa-minus-square text-red-500' }} mr-2"></i>
                                        @else
                                            <i class="far {{ $option->is_correct ? 'fa-check-circle text-green-400' : 'fa-circle text-gray-400' }} mr-2"></i>
                                        @endif
                                    @else
                                        {{-- Jika student tidak menjawab --}}
                                         <i class="far fa-circle text-gray-400 mr-2"></i>
                                    @endif
                                    <span>{{ $option->option_text }}</span>
                                    @if($option->is_correct) <span class="ml-2 text-xs">(Kunci Jawaban)</span> @endif
                                </li>
                            @endforeach
                        </ul>
                        @if($studentAnswer && $question->question_type === 'single_choice' && $studentAnswer->answer_option_id && !$studentAnswer->is_correct)
                            @php $correctOptText = $question->answerOptions->where('is_correct', true)->first()->option_text ?? ''; @endphp
                            {{-- <p class="text-xs text-green-600 mt-1">Jawaban benar: {{ $correctOptText }}</p> --}}
                        @endif
                    @elseif($question->question_type === 'essay')
                        <p class="text-xs font-semibold text-gray-600 mb-1">Jawaban Anda:</p>
                        <div class="p-2 border rounded bg-gray-100 text-sm text-gray-800">
                            {!! nl2br(e($studentAnswer->answer_text ?? 'Tidak dijawab')) !!}
                        </div>
                        @if($studentAnswer && $studentAnswer->is_correct !== null)
                            <p class="text-xs mt-1">Dinilai: {{ $studentAnswer->is_correct ? 'Benar' : 'Salah' }}, Poin: {{ $studentAnswer->points_awarded ?? 0 }}</p>
                        @else
                             <p class="text-xs mt-1 text-yellow-600">Menunggu penilaian dari mentor.</p>
                        @endif
                    @endif
                    
                    @if($studentAnswer)
                    <p class="text-xs mt-2">Poin Diperoleh: <span class="font-semibold">{{ $studentAnswer->points_awarded ?? 0 }}</span></p>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection