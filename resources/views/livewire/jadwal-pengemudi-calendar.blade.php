<div class="w-full">
    <div class="font-sans w-full">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">Jadwal Perjalanan Pengemudi</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-base">Pantau dan kelola jadwal perjalanan staf pengemudi secara efisien.</p>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden flex flex-col min-h-[500px] w-full shadow-xl rounded-xl border border-gray-200 dark:border-gray-700">

            {{-- Toolbar for Filters and Search --}}
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 dark:bg-gray-700/50 z-20">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Month Dropdown --}}
                    <select wire:model.live="selectedMonth" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm px-4 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">{{ Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}</option>
                        @endforeach
                    </select>

                    {{-- Year Dropdown --}}
                    <select wire:model.live="selectedYear" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm px-4 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Input --}}
                <div class="w-full sm:w-auto">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari pengemudi..."
                        class="w-full sm:w-64 px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out placeholder-gray-500 dark:placeholder-gray-400"
                    >
                </div>
            </div>

            {{-- Table Container --}}
            <div
                x-data="{
                    stafOrder: @entangle('manualSortOrder').defer,
                    init() {
                        new Sortable(this.$refs.stafTableBody, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: (evt) => {
                                this.stafOrder = Array.from(this.$refs.stafTableBody.children).map(row => row.dataset.stafId);
                                $wire.dispatch('update-staf-sort', { newOrder: this.stafOrder });
                            }
                        });
                    }
                }"
                class="flex-grow overflow-x-auto relative custom-scrollbar"
            >
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-30 bg-gray-100 dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            {{-- Sticky Driver Column Header --}}
                            <th class="sticky left-0 z-40 bg-gray-100 dark:bg-gray-800 p-4 min-w-[250px] border-r border-gray-200 dark:border-gray-700 shadow-[4px_0_15px_-4px_rgba(0,0,0,0.1)]">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pengemudi</span>
                            </th>

                            {{-- Dates Header --}}
                            @foreach ($dates as $dateString)
                                @php
                                    $date = Carbon\Carbon::parse($dateString);
                                    $dayName = $date->locale('id')->translatedFormat('D');
                                    $isWeekend = $date->isWeekend();
                                    $isToday = $date->isToday();
                                @endphp
                                <th class="p-3 min-w-[160px] text-center border-r border-gray-200 dark:border-gray-700 last:border-r-0 {{ $isWeekend ? 'bg-gray-50 dark:bg-gray-900' : '' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase">{{ $dayName }}</span>
                                        <span @class([
                                            "text-base font-bold w-9 h-9 flex items-center justify-center rounded-full transition-colors duration-200 ease-in-out",
                                            "text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-400" => $isWeekend,
                                            "text-gray-800 dark:text-gray-200" => !$isWeekend,
                                            "bg-blue-600 !text-white shadow-md" => $isToday,
                                        ])>
                                            {{ $date->day }}
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody x-ref="stafTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($drivers as $driver)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150 ease-in-out" data-staf-id="{{ $driver['staf_id'] }}">
                                {{-- Sticky Driver Column Body --}}
                                <td class="sticky left-0 z-20 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/30 p-3 px-4 border-r border-gray-200 dark:border-gray-700 shadow-[4px_0_15px_-4px_rgba(0,0,0,0.08)]">
                                    <div class="flex items-center gap-3">
                                        <button type="button" class="drag-handle p-0.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-grab rounded-md -ml-1 transition-colors duration-200">
                                            <x-heroicon-o-bars-3 class="w-4 h-4" />
                                        </button>
                                        <div class="flex-shrink-0">
                                            {{-- You can add an avatar component here if available --}}
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800/50 flex items-center justify-center text-blue-800 dark:text-blue-300 font-semibold text-sm">
                                                {{ Str::limit($driver['nama_staf'], 1, '') }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100 leading-snug whitespace-nowrap overflow-hidden text-ellipsis">{{ $driver['nama_staf'] }}</div>
                                            {{-- <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $driver['staf_id'] }}</p> --}}
                                        </div>
                                    </div>
                                </td>

                                {{-- Perjalanan Cells --}}
                                @foreach ($dates as $dateString)
                                    @php
                                        $cellPerjalanans = $perjalanansByDriverAndDate[$driver['staf_id']][$dateString] ?? [];
                                        $isWeekend = Carbon\Carbon::parse($dateString)->isWeekend();
                                    @endphp
                                    <td class="p-2 border-r border-gray-100 dark:border-gray-700/50 last:border-r-0 h-28 relative align-top {{ $isWeekend ? 'bg-gray-50 dark:bg-gray-900/50' : '' }}">
                                        @forelse ($cellPerjalanans as $perjalanan)
                                            <div
                                                class="group-card absolute inset-1 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg flex flex-col justify-center cursor-pointer p-2 text-xs shadow-sm
                                                hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:shadow-md transition-all duration-200 ease-in-out transform hover:-translate-y-0.5"
                                                title="Nomor: {{ $perjalanan['nomor_perjalanan'] }} | {{ $perjalanan['merk_type'] }} ({{ $perjalanan['nopol_kendaraan'] }}) | {{ $perjalanan['kota_kabupaten'] }}"
                                            >
                                                <p class="font-bold text-blue-800 dark:text-blue-200 truncate leading-tight">#{{ $perjalanan['nomor_perjalanan'] }}</p>
                                                <p class="text-blue-700 dark:text-blue-300 truncate leading-tight">{{ $perjalanan['merk_type'] }}</p>
                                                <p class="text-blue-600 dark:text-blue-400 truncate leading-tight">({{ $perjalanan['nopol_kendaraan'] }})</p>
                                                <p class="text-blue-500 dark:text-blue-500 truncate leading-tight">{{ $perjalanan['kota_kabupaten'] }}</p>
                                            </div>
                                        @empty
                                            <div class="flex items-center justify-center h-full text-gray-400 dark:text-gray-600 italic text-xs">
                                                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Kosong</span>
                                            </div>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($dates) + 2 }}" class="text-center p-16 text-gray-500 dark:text-gray-400">
                                    <x-heroicon-o-inbox class="w-16 h-16 mx-auto mb-6 text-gray-400 dark:text-gray-600"/>
                                    <p class="text-lg font-medium">Tidak ada jadwal pengemudi ditemukan.</p>
                                    <p class="text-sm mt-2">Coba sesuaikan filter bulan atau tahun, atau tambahkan jadwal baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 text-sm text-gray-600 dark:text-gray-400 flex justify-between items-center rounded-b-xl">
                <span>Menampilkan {{ count($drivers) }} pengemudi dengan perjalanan</span>
                {{-- Pagination or other footer elements can go here --}}
            </div>
        </div>
    </div>
    <style>
/* Custom scrollbar for better aesthetics */
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

@media (prefers-color-scheme: dark) {
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #374151; /* dark gray */
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #6b7280; /* medium dark gray */
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; /* light dark gray */
    }
}
</style>
</div>
