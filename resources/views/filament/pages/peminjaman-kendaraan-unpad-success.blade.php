@extends('public-form-layout')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    {{-- UNPAD Logo --}}
    <div class="flex justify-center mb-4">
        <img src="/images/Unpad_logo.png" alt="Logo Universitas Padjadjaran" class="h-16 w-auto">
    </div>

    <h1 class="text-2xl font-bold text-center mb-2 text-gray-800 dark:text-gray-200">Formulir Peminjaman Kendaraan Universitas Padjadjaran</h1>
    <p class="text-center mb-8 text-gray-600 dark:text-gray-400">Direktorat Pengelolaan Aset dan Sarana Prasarana</p>

    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-green-800 mb-2">Pengajuan Anda telah berhasil dikirim!</h2>
        <p class="text-green-700 mb-4">Silakan simpan link berikut untuk melacak progres persetujuan Anda:</p>
        <div class="bg-white border rounded p-3 mb-4">
            <code class="text-sm text-gray-800">{{ url('/status/' . $token) }}</code>
        </div>
        <p class="text-xs text-gray-600">Simpan token ini untuk melacak status pengajuan Anda.</p>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('peminjaman.form') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Kembali ke Formulir
        </a>
    </div>
</div>
@endsection
