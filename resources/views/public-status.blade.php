@extends('public-form-layout')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    {{-- UNPAD Logo --}}
    <div class="flex justify-center mb-4">
        <img src="/images/Unpad_logo.png" alt="Logo Universitas Padjadjaran" class="h-16 w-auto">
    </div>

    <h1 class="text-2xl font-bold text-center mb-2 text-gray-800 dark:text-gray-200">Status Pengajuan Peminjaman Kendaraan</h1>
    <p class="text-center mb-8 text-gray-600 dark:text-gray-400">Direktorat Pengelolaan Aset dan Sarana Prasarana</p>

    @if($perjalanan)
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
            <h2 class="text-lg font-semibold mb-4">Detail Pengajuan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <strong>Nama Kegiatan:</strong><br>
                    {{ $perjalanan->nama_kegiatan }}
                </div>
                <div>
                    <strong>Tujuan:</strong><br>
                    {{ $perjalanan->alamat_tujuan }}, {{ $perjalanan->wilayah->nama_wilayah ?? '' }}, {{ $perjalanan->provinsi }}
                </div>
                <div>
                    <strong>Waktu Keberangkatan:</strong><br>
                    {{ $perjalanan->waktu_keberangkatan->format('d M Y H:i') }}
                </div>
                <div>
                    <strong>Waktu Kepulangan:</strong><br>
                    {{ $perjalanan->waktu_kepulangan ? $perjalanan->waktu_kepulangan->format('d M Y H:i') : 'Tidak ditentukan' }}
                </div>
                <div>
                    <strong>Jumlah Rombongan:</strong><br>
                    {{ $perjalanan->jumlah_rombongan }}
                </div>
                <div>
                    <strong>Status:</strong><br>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($perjalanan->status_perjalanan == 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                        @elseif($perjalanan->status_perjalanan == 'Disetujui') bg-green-100 text-green-800
                        @elseif($perjalanan->status_perjalanan == 'Ditolak') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $perjalanan->status_perjalanan }}
                    </span>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600">
                    Jika ada pertanyaan, silakan hubungi Direktorat Pengelolaan Aset dan Sarana Prasarana Universitas Padjadjaran.
                </p>
            </div>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-red-800 mb-2">Pengajuan Tidak Ditemukan</h2>
            <p class="text-red-700">Token yang Anda masukkan tidak valid atau pengajuan telah dihapus.</p>
        </div>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('peminjaman.form') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Kembali ke Formulir
        </a>
    </div>
</div>
@endsection
