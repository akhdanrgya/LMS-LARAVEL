{{-- File: resources/views/layouts/partials/alerts.blade.php --}}

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-md" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
            <div>
                <p class="font-bold">Sukses!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-md" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-times-circle fa-lg mr-3 text-red-500"></i></div>
            <div>
                <p class="font-bold">Error!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded-md shadow-md" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-triangle fa-lg mr-3 text-yellow-500"></i></div>
            <div>
                <p class="font-bold">Peringatan!</p>
                <p class="text-sm">{{ session('warning') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-md shadow-md" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-info-circle fa-lg mr-3 text-blue-500"></i></div>
            <div>
                <p class="font-bold">Informasi</p>
                <p class="text-sm">{{ session('info') }}</p>
            </div>
        </div>
    </div>
@endif

{{-- Kamu juga bisa nambahin ini buat nampilin error validasi secara umum, --}}
{{-- tapi biasanya error validasi per field lebih enak ditampilin di deket field form-nya langsung --}}
{{-- @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-md" role="alert">
        <p class="font-bold">Ada beberapa hal yang perlu diperbaiki:</p>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}