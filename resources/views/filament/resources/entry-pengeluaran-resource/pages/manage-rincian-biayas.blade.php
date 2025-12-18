<x-filament-panels::page>
    {{ $this->infolist }}

    @php
        $rincianBiayas = $this->rincianPengeluaran->rincianBiayas;
        $bbmRincianBiayas = $rincianBiayas->where('tipe', 'bbm');
        $tollRincianBiayas = $rincianBiayas->where('tipe', 'toll');
        $parkirRincianBiayas = $rincianBiayas->where('tipe', 'parkir');
    @endphp

    <div class="mt-8 space-y-6">
        {{-- BBM Card View --}}
        <div class="filament-tables-card p-4 rounded-xl shadow-sm bg-white dark:bg-gray-800">
            <h3 class="text-lg font-bold mb-4 dark:text-white">Rincian Biaya BBM</h3>

            @if ($bbmRincianBiayas->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">Tidak ada rincian biaya BBM.</p>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($bbmRincianBiayas as $rincianBiaya)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Biaya</p>
                            <p class="text-lg font-semibold text-primary-600 dark:text-primary-400 mb-2">Rp{{ number_format($rincianBiaya->biaya, 0, ',', '.') }}</p>

                            <p class="text-sm text-gray-500 dark:text-gray-400">Jenis BBM</p>
                            <p class="text-base font-medium dark:text-white mb-2">{{ $rincianBiaya->jenis_bbm }}</p>

                            <p class="text-sm text-gray-500 dark:text-gray-400">Volume</p>
                            <p class="text-base font-medium dark:text-white mb-2">{{ $rincianBiaya->volume }} Ltr</p>

                            <p class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</p>
                            <p class="text-base dark:text-white">{{ $rincianBiaya->deskripsi }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-primary-50 dark:bg-primary-900 rounded-lg shadow-inner flex justify-between items-center">
                    <p class="text-lg font-bold text-primary-800 dark:text-primary-200">Total Biaya BBM:</p>
                    <p class="text-xl font-bold text-primary-800 dark:text-primary-200">Rp{{ number_format($bbmRincianBiayas->sum('biaya'), 0, ',', '.') }}</p>
                </div>
            @endif
        </div>

        {{-- Toll Card View --}}
        <div class="filament-tables-card p-4 rounded-xl shadow-sm bg-white dark:bg-gray-800">
            <h3 class="text-lg font-bold mb-4 dark:text-white">Rincian Biaya Toll</h3>

            @if ($tollRincianBiayas->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">Tidak ada rincian biaya Toll.</p>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($tollRincianBiayas as $rincianBiaya)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Biaya</p>
                            <p class="text-lg font-semibold text-primary-600 dark:text-primary-400 mb-2">Rp{{ number_format($rincianBiaya->biaya, 0, ',', '.') }}</p>

                            <p class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</p>
                            <p class="text-base dark:text-white">{{ $rincianBiaya->deskripsi }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-primary-50 dark:bg-primary-900 rounded-lg shadow-inner flex justify-between items-center">
                    <p class="text-lg font-bold text-primary-800 dark:text-primary-200">Total Biaya Toll:</p>
                    <p class="text-xl font-bold text-primary-800 dark:text-primary-200">Rp{{ number_format($tollRincianBiayas->sum('biaya'), 0, ',', '.') }}</p>
                </div>
            @endif
        </details>

        {{-- Parkir Card View --}}
        <details class="filament-tables-card p-4 rounded-xl shadow-sm bg-white dark:bg-gray-800">
            <summary><h3 class="text-lg font-bold mb-4 text-gray-600 dark:text-gray-300 cursor-pointer">Rincian Biaya Parkir</h3></summary>

            @if ($parkirRincianBiayas->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">Tidak ada rincian biaya Parkir.</p>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($parkirRincianBiayas as $rincianBiaya)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Biaya</p>
                            <p class="text-lg font-semibold text-primary-600 dark:text-primary-400 mb-2">Rp{{ number_format($rincianBiaya->biaya, 0, ',', '.') }}</p>

                            <p class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</p>
                            <p class="text-base dark:text-white">{{ $rincianBiaya->deskripsi }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-primary-50 dark:bg-primary-900 rounded-lg shadow-inner flex justify-between items-center">
                    <p class="text-lg font-bold text-primary-800 dark:text-primary-200">Total Biaya Parkir:</p>
                    <p class="text-xl font-bold text-primary-800 dark:text-primary-200">Rp{{ number_format($parkirRincianBiayas->sum('biaya'), 0, ',', '.') }}</p>
                </div>
            @endif
        </details>
    </div>
</x-filament-panels::page>
