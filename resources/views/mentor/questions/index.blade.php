@extends('layouts.layout') {{-- Pastikan ini nama layout utama lo --}}

@section('title', 'Kelola Pertanyaan untuk Quiz: ' . $quiz->title)

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
@include('components.header')

{{-- PASTIKAN BLOK PHP INI ADA SEBELUM TAG <script> ALPINE.JS --}}
@php
    // Definisikan array default buat options
    $defaultAlpineOptions = [['text' => '', 'is_correct' => false], ['text' => '', 'is_correct' => false]];
    // Ambil data 'options' dari old input, atau pake default kalo gak ada
    $optionsForAlpine = old('options', $defaultAlpineOptions);
@endphp

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('mentor.courses.quizzes.index', $course->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-1 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Quiz (Course: {{ $course->title }})
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Kelola Pertanyaan: <span class="text-indigo-700">{{ $quiz->title }}</span>
        </h1>
    </div>

    @include('layouts.partials.alerts')

    {{-- FORM TAMBAH PERTANYAAN BARU --}}
    <div class="bg-white p-6 rounded-xl shadow-xl mb-8" x-data="questionForm()">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah Pertanyaan Baru</h2>
        <form action="{{ route('mentor.courses.quizzes.questions.store', ['course' => $course->slug, 'quiz' => $quiz->id]) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="question_text" class="block text-sm font-medium text-gray-700">Teks Pertanyaan <span class="text-red-500">*</span></label>
                <textarea name="question_text" id="question_text" rows="3" required 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('question_text') border-red-500 @enderror">{{ old('question_text') }}</textarea>
                @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="question_type" class="block text-sm font-medium text-gray-700">Tipe Pertanyaan <span class="text-red-500">*</span></label>
                    <select name="question_type" id="question_type" x-model="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('question_type') border-red-500 @enderror">
                        <option value="single_choice">Pilihan Tunggal (Satu Jawaban Benar)</option>
                        <option value="multiple_choice">Pilihan Ganda (Banyak Jawaban Benar)</option>
                        <option value="essay">Esai</option>
                    </select>
                    @error('question_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700">Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="points" id="points" value="{{ old('points', 10) }}" required min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('points') border-red-500 @enderror">
                    @error('points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Bagian untuk Pilihan Jawaban (Muncul jika tipe PG/Single) --}}
            <div x-show="type === 'multiple_choice' || type === 'single_choice'" class="mb-4 p-4 border rounded-md bg-gray-50">
                <h3 class="text-md font-semibold text-gray-700 mb-3">Pilihan Jawaban (<span x-text="minOptions"></span>-<span x-text="maxOptions"></span> pilihan):</h3>
                @error('options') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                
                <template x-for="(option, index) in options" :key="index">
                    <div class="flex items-center mb-2 gap-2">
                        {{-- Input Teks Pilihan Jawaban --}}
                        <input type="text" :name="'options[' + index + '][text]'" x-model="option.text" 
                               placeholder="Teks Pilihan Jawaban" 
                               class="flex-grow rounded-md border-gray-300 shadow-sm text-sm 
                                      {{-- Logika buat nampilin border merah kalo ada error bisa lebih kompleks --}}
                                      {{-- Contoh: :class="{'border-red-500': errors && errors['options.'+index+'.text']}" --}}
                                      ">
                        
                        {{-- Input Visual untuk Menandai Jawaban Benar (Radio atau Checkbox) --}}
                        <label :for="'visual_correct_marker_' + index" class="flex items-center text-sm cursor-pointer">
                            <input 
                                :type="type === 'multiple_choice' ? 'checkbox' : 'radio'"
                                :name="'visual_correct_marker_for_type_' + type + (type === 'single_choice' ? '' : '_' + index)" 
                                :id="'visual_correct_marker_' + index"
                                @change="toggleCorrect(index)"
                                :checked="option.is_correct"
                                class="mr-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            Benar?
                        </label>
                        
                        {{-- INPUT HIDDEN untuk submit nilai is_correct yang sebenarnya --}}
                        <input type="hidden" :name="'options[' + index + '][is_correct]'" :value="option.is_correct ? '1' : '0'">
                        
                        {{-- Tombol Hapus Pilihan --}}
                        <button type="button" @click="removeOption(index)" x-show="options.length > minOptions"
                                class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-100">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                    {{-- BARIS @error('options.'+index+'.text') DIHAPUS DARI SINI --}}
                    {{-- Kalo mau nampilin error per field di sini, butuh cara lain (misal lewat Alpine.js) --}}
                </template>
                <button type="button" @click="addOption()" x-show="options.length < maxOptions"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 border border-indigo-300 hover:bg-indigo-50 px-3 py-1 rounded-md">
                    + Tambah Pilihan
                </button>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                    Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>

    {{-- DAFTAR PERTANYAAN YANG SUDAH ADA --}}
    <div class="bg-white p-6 rounded-xl shadow-xl">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Pertanyaan ({{ $questions->count() }})</h2>
        @if($questions->isEmpty())
            <p class="text-gray-600">Belum ada pertanyaan untuk quiz ini.</p>
        @else
            <div class="space-y-4">
                @foreach ($questions as $idx => $question) {{-- Ganti $index jadi $idx biar gak bentrok sama var index di Alpine --}}
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500">No. {{ $loop->iteration }} (Poin: {{ $question->points }}) - Tipe: {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</p>
                            <p class="text-md font-medium text-gray-800 mt-1">{!! nl2br(e($question->question_text)) !!}</p>
                        </div>
                        <div class="flex space-x-2 flex-shrink-0">
                            <a href="{{ route('mentor.courses.quizzes.questions.edit', ['course' => $course->slug, 'quiz' => $quiz->id, 'question' => $question->id]) }}" 
                            class="text-yellow-600 hover:text-yellow-800" title="Edit Pertanyaan">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('mentor.courses.quizzes.questions.destroy', ['course' => $course->slug, 'quiz' => $quiz->id, 'question' => $question->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus pertanyaan ini?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus Pertanyaan">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @if($question->question_type !== 'essay' && $question->answerOptions->isNotEmpty())
                        <div class="mt-3 pl-4">
                            <p class="text-xs font-semibold text-gray-600 mb-1">Pilihan Jawaban:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($question->answerOptions as $opt)
                                <li class="text-sm {{ $opt->is_correct ? 'text-green-600 font-semibold' : 'text-gray-700' }}">
                                    {{ $opt->option_text }} {{ $opt->is_correct ? '(Jawaban Benar)' : '' }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Tag <script> dan isinya persis sama dengan yang lo kasih sebelumnya --}}
    <script>
        function questionForm() {
            // Ambil data options dari PHP, termasuk old input jika ada, atau default jika tidak ada
            let initialOptions = @json($optionsForAlpine);
            let initialType = '{{ old('question_type', 'single_choice') }}';
    
            // Jika tipe esai dan initialOptions masih default (2 pilihan kosong), maka kosongkan.
            if (initialType === 'essay' && initialOptions.length === 2 && initialOptions.every(opt => opt.text === '' && opt.is_correct === false)) {
                initialOptions = [];
            }
            // Jika tipe bukan esai dan initialOptions kosong, buat default minimal.
            else if (initialType !== 'essay' && initialOptions.length === 0) {
                initialOptions = Array.from({length: 2}, () => ({text: '', is_correct: false}));
            }
    
    
            return {
                type: initialType,
                options: initialOptions,
                minOptions: 2,
                maxOptions: 5, // Atur maksimal pilihan jawaban
                
                init() {
                    // Panggil toggleCorrect sekali di init untuk memastikan state awal radio button benar jika ada old input
                    // atau jika ada data dari $question (untuk form edit nantinya)
                    if (this.type === 'single_choice') {
                        let correctIndex = -1;
                        this.options.forEach((opt, idx) => {
                            if (opt.is_correct) {
                                correctIndex = idx;
                            }
                        });
                        if (correctIndex !== -1) {
                            this.toggleCorrect(correctIndex); // Set initial correct state
                        }
                    }
    
                    this.$watch('type', newType => {
                        if (newType === 'essay') {
                            this.options = [];
                        } else if (this.options.length < this.minOptions) { 
                            // Jika pindah dari essay ke pilihan, atau options kosong, tambahkan opsi default
                            for (let i = this.options.length; i < this.minOptions; i++) {
                                this.options.push({ text: '', is_correct: false });
                            }
                        }
                        // Jika tipe baru adalah single_choice, pastikan hanya satu (atau nol) yang is_correct
                        if (newType === 'single_choice') {
                            let foundCorrect = false;
                            this.options.forEach(opt => {
                                if (opt.is_correct) {
                                    if (foundCorrect) opt.is_correct = false; // Hanya satu
                                    foundCorrect = true;
                                }
                            });
                        }
                    });
                },
                
                toggleCorrect(selectedIndex) {
                    if (this.type === 'single_choice') {
                        this.options.forEach((option, idx) => {
                            option.is_correct = (idx === selectedIndex);
                        });
                    } else if (this.type === 'multiple_choice') {
                        // Untuk checkbox, toggle state-nya
                        this.options[selectedIndex].is_correct = !this.options[selectedIndex].is_correct;
                    }
                    // Untuk tipe essay, tidak ada is_correct
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