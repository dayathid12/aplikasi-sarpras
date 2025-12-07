@php
    $suratPath = $getRecord()->surat_peminjaman_kendaraan;
    $dokumenPath = $getRecord()->dokumen_pendukung;

    $suratUrl = $suratPath ? Illuminate\Support\Facades\Storage::url($suratPath) : null;
    $dokumenUrl = $dokumenPath ? Illuminate\Support\Facades\Storage::url($dokumenPath) : null;

    $isSuratImage = $suratUrl && in_array(strtolower(pathinfo($suratPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']);
    $isSuratPdf = $suratUrl && strtolower(pathinfo($suratPath, PATHINFO_EXTENSION)) === 'pdf';

    $isDokumenImage = $dokumenUrl && in_array(strtolower(pathinfo($dokumenPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']);
    $isDokumenPdf = $dokumenUrl && strtolower(pathinfo($dokumenPath, PATHINFO_EXTENSION)) === 'pdf';
@endphp

<div class="space-y-6 col-span-full">
    @if ($suratUrl)
        <div class="p-4 border rounded-lg shadow-sm">
            <h4 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">Pratinjau Surat Peminjaman Kendaraan</h4>
            @if ($isSuratImage)
                <div class="flex justify-center">
                    <img src="{{ $suratUrl }}" alt="Preview Surat Peminjaman" class="max-w-lg h-auto rounded-lg border">
                </div>
            @elseif ($isSuratPdf)
                <iframe src="{{ $suratUrl }}" width="100%" height="600px" class="border rounded-lg"></iframe>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">PDF di atas. Jika tidak muncul, Anda dapat <a href="{{ $suratUrl }}" target="_blank" class="text-primary-600 underline">membukanya di tab baru</a>.</p>
            @else
                <div class="flex items-center justify-center p-4 text-center bg-gray-50 rounded-lg dark:bg-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pratinjau tidak tersedia untuk tipe file ini. <a href="{{ $suratUrl }}" target="_blank" class="font-medium text-primary-600 underline">Lihat/Unduh file</a>.</p>
                </div>
            @endif
        </div>
    @endif

    @if ($dokumenUrl)
        <div class="p-4 border rounded-lg shadow-sm">
            <h4 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">Pratinjau Dokumen Pendukung</h4>
            @if ($isDokumenImage)
                <div class="flex justify-center">
                    <img src="{{ $dokumenUrl }}" alt="Preview Dokumen Pendukung" class="max-w-lg h-auto rounded-lg border">
                </div>
            @elseif ($isDokumenPdf)
                <iframe src="{{ $dokumenUrl }}" width="100%" height="600px" class="border rounded-lg"></iframe>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">PDF tertanam di atas. Jika tidak muncul, Anda dapat <a href="{{ $dokumenUrl }}" target="_blank" class="text-primary-600 underline">membukanya di tab baru</a>.</p>
            @else
                <div class="flex items-center justify-center p-4 text-center bg-gray-50 rounded-lg dark:bg-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pratinjau tidak tersedia untuk tipe file ini. <a href="{{ $dokumenUrl }}" target="_blank" class="font-medium text-primary-600 underline">Lihat/Unduh file</a>.</p>
                </div>
            @endif
        </div>
    @endif

    @if (!$suratUrl && !$dokumenUrl)
        <div class="p-4 text-center bg-gray-50 rounded-lg dark:bg-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada file yang diunggah untuk pratinjau.</p>
        </div>
    @endif
</div>
