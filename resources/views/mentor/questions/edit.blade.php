@extends('layouts.layout') {{-- Ganti 'layouts.app' dengan nama file layout utama lo kalo beda --}}

@section('title', 'Edit Pertanyaan di Quiz: ' . $quiz->title)

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('mentor.courses.quizzes.questions.index', ['course' => $course->slug, 'quiz' => $quiz->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-1 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Kelola Pertanyaan (Quiz: {{ $quiz->title }})
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Edit Pertanyaan
        </h1>
        <p class="text-sm text-gray-600 mt-1">Untuk Quiz: <span class="font-semibold">{{ $quiz->title }}</span> di Course: <span class="font-semibold">{{ $course->title }}</span></p>
    </div>

    @include('layouts.partials.alerts')

    {{-- FORM EDIT PERTANYAAN --}}
    <div class="bg-white p-6 rounded-xl shadow-xl mb-8" x-data="questionFormEdit()">
        <form action="{{ route('mentor.courses.quizzes.questions.update', ['course' => $course->slug, 'quiz' => $quiz->id, 'question' => $question->id]) }}" method="POST">
            @csrf
            @method('PUT') {{-- Method spoofing untuk UPDATE --}}

            <div class="mb-4">
                <label for="question_text" class="block text-sm font-medium text-gray-700">Teks Pertanyaan <span class="text-red-500">*</span></label>
                <textarea name="question_text" id="question_text" rows="3" required 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('question_text') border-red-500 @enderror">{{ old('question_text', $question->question_text) }}</textarea>
                @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="question_type" class="block text-sm font-medium text-gray-700">Tipe Pertanyaan <span class="text-red-500">*</span></label>
                    <select name="question_type" id="question_type" x-model="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('question_type') border-red-500 @enderror">
                        <option value="single_choice" {{ old('question_type', $question->question_type) == 'single_choice' ? 'selected' : '' }}>Pilihan Tunggal</option>
                        <option value="multiple_choice" {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="essay" {{ old('question_type', $question->question_type) == 'essay' ? 'selected' : '' }}>Esai</option>
                    </select>
                    @error('question_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700">Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="points" id="points" value="{{ old('points', $question->points) }}" required min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('points') border-red-500 @enderror">
                    @error('points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Bagian untuk Pilihan Jawaban (Muncul jika tipe PG/Single) --}}
            <div x-show="type === 'multiple_choice' || type === 'single_choice'" class="mb-4 p-4 border rounded-md bg-gray-50">
                <h3 class="text-md font-semibold text-gray-700 mb-3">Pilihan Jawaban (<span x-text="minOptions"></span>-<span x-text="maxOptions"></span> pilihan):</h3>
                 @error('options') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                @if ($errors->has('options.*'))
                    <div class="text-red-500 text-xs mb-2">
                        @foreach ($errors->get('options.*') as $messages)
                            @foreach ($messages as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        @endforeach
                    </div>
                @endif
                
                <template x-for="(option, index) in options" :key="index">
                    <div class="flex items-center mb-2 gap-2">
                        <input type="text" :name="'options[' + index + '][text]'" x-model="option.text" placeholder="Teks Pilihan Jawaban" 
                               class="flex-grow rounded-md border-gray-300 shadow-sm text-sm @error('options.' + index + '.text') border-red-500 @enderror">
                        <label :for="'visual_correct_marker_' + index" class="flex items-center text-sm cursor-pointer">
                            <input 
                                :type="type === 'multiple_choice' ? 'checkbox' : 'radio'"
                                {{-- Perbaikan: Nama radio unik per grup soal (quiz id + question type) --}}
                                {{-- atau cukup pastikan `toggleCorrect` menangani logika single choice dengan benar --}}
                                :name="'visual_correct_marker_group_' + (type === 'single_choice' ? 'q{{ $question->id }}' : index)" 
                                :id="'visual_correct_marker_' + index"
                                @change="toggleCorrect(index)"
                                :checked="option.is_correct"
                                class="mr-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            Benar?
                        </label>
                        <input type="hidden" :name="'options[' + index + '][is_correct]'" :value="option.is_correct ? '1' : '0'">
                        <button type="button" @click="removeOption(index)" x-show="options.length > minOptions"
                                class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                    {{-- Nampilin error per option text, ini lebih kompleks pake Alpine dan $errors Laravel --}}
                    {{-- Untuk sekarang, error 'options.*' di atas sudah cukup --}}
                </template>
                <button type="button" @click="addOption()" x-show="options.length < maxOptions"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 border border-indigo-300 hover:bg-indigo-50 px-3 py-1 rounded-md">
                    + Tambah Pilihan
                </button>
            </div>
            <div class="mt-6 flex items-center justify-end space-x-4">
                 <a href="{{ route('mentor.courses.quizzes.questions.index', ['course' => $course->slug, 'quiz' => $quiz->id]) }}" class="text-gray-600 hover:text-gray-800">Batal</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                    Update Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function questionFormEdit() {
        // Ambil data question type yang ada (dari old input atau dari $question)
        let currentQuestionType = '{{ old('question_type', $question->question_type) }}';
        
        // Ambil data options yang ada
        // 1. Cek dulu apakah ada old('options') (berarti ada error validasi sebelumnya)
        // 2. Kalo gak ada old('options'), baru ambil dari $question->answerOptions
        // 3. Kalo dua-duanya gak ada dan tipe soalnya pilihan, buat array kosong default
        let initialOptionsData = @json(old('options')); // Ini akan jadi null jika tidak ada old('options')

        if (initialOptionsData === null) { // Jika tidak ada old input untuk options
            initialOptionsData = @json($question->answerOptions->map(function($opt) {
                return ['text' => $opt->option_text, 'is_correct' => (bool)$opt->is_correct];
            })->all());
        }

        // Jika tipe soalnya bukan esai dan initialOptionsData masih kosong (misalnya question baru belum ada options, atau dari db emang kosong),
        // kita kasih default 2 pilihan kosong biar formnya gak aneh.
        if (currentQuestionType !== 'essay' && (!initialOptionsData || initialOptionsData.length === 0)) {
            initialOptionsData = Array.from({length: 2}, () => ({text: '', is_correct: false}));
        } else if (currentQuestionType === 'essay') {
            initialOptionsData = []; // Pastikan kosong jika essay
        }

        return {
            type: currentQuestionType,
            options: JSON.parse(JSON.stringify(initialOptionsData)), // Deep clone
            minOptions: 2,
            maxOptions: 5,
            
            init() {
                // Pastikan state awal 'is_correct' untuk single_choice benar
                if (this.type === 'single_choice') {
                    let correctFoundAndSet = false;
                    this.options.forEach(opt => {
                        if (opt.is_correct) {
                            if (correctFoundAndSet) {
                                opt.is_correct = false; // Hanya satu yang boleh true
                            } else {
                                correctFoundAndSet = true;
                            }
                        }
                    });
                }

                this.$watch('type', newType => {
                    if (newType === 'essay') {
                        this.options = [];
                    } else {
                        // Jika options kosong atau kurang dari minOptions, tambahkan
                        if (this.options.length < this.minOptions) {
                            for (let i = this.options.length; i < this.minOptions; i++) {
                                this.options.push({ text: '', is_correct: false });
                            }
                        }
                        // Jika tipe baru adalah single_choice, pastikan hanya satu (atau nol) yang is_correct
                        if (newType === 'single_choice') {
                            let foundCorrect = false;
                            this.options.forEach(opt => {
                                if (opt.is_correct) {
                                    if (foundCorrect) opt.is_correct = false;
                                    foundCorrect = true;
                                }
                            });
                        }
                    }
                });
            },
            
            toggleCorrect(selectedIndex) { 
                if (this.type === 'single_choice') {
                    this.options.forEach((option, idx) => {
                        option.is_correct = (idx === selectedIndex);
                    });
                } else if (this.type === 'multiple_choice') {
                    this.options[selectedIndex].is_correct = !this.options[selectedIndex].is_correct;
                }
            },
            addOption() { 
                if (this.options.length < this.maxOptions) {
                    this.options.push({ text: '', is_correct: false });
                }
            },
            removeOption(index) { 
                if (this.options.length > this.minOptions) {
                    this.options.splice(index, 1);
                }
            }
        }
    }
</script>
@endsection