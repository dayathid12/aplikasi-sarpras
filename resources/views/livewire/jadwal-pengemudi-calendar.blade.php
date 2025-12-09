<x-filament-panels::page>
    <style>
        /* CSS Tambahan untuk scrollbar & animasi */
        .no-scrollbar::-webkit-scrollbar { height: 8px; }
        .no-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .no-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    </style>

    <div class="font-sans">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-gray-100">Jadwal Operasional</h1>
                <p class="text-slate-500 dark:text-gray-400 mt-1 text-sm">Kelola penugasan pengemudi harian Anda di sini.</p>
            </div>
            {{-- Tombol di header bisa diaktifkan jika perlu --}}
            {{-- <button class="bg-teal-600 hover:bg-teal-700 active:scale-95 transform transition-all text-white px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md text-sm font-medium flex items-center gap-2">
                <span>Perjalanan Detail</span>
            </button> --}}
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-slate-200 dark:border-gray-700 overflow-hidden flex flex-col h-[calc(100vh-220px)] min-h-[500px]">
            
            {{-- Toolbar --}}
            <div class="p-4 md:p-5 border-b border-slate-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 z-20">
                
                {{-- Month Navigator --}}
                <div class="flex items-center gap-2 bg-slate-50 dark:bg-gray-700/50 px-1 py-1 rounded-lg border border-slate-200 dark:border-gray-600 w-full sm:w-auto justify-between sm:justify-start">
                    <button wire:click="previousMonth" class="p-2 hover:bg-white dark:hover:bg-gray-600 hover:shadow-sm rounded-md transition-all text-slate-500 dark:text-gray-400 hover:text-teal-600">
                        <x-heroicon-o-chevron-left class="w-5 h-5"/>
                    </button>
                    <div class="flex items-center gap-2 font-semibold text-slate-700 dark:text-gray-300 px-2 select-none">
                        <x-heroicon-o-calendar class="w-5 h-5 text-teal-600"/>
                        <span>{{ $this->currentDate->locale('id')->translatedFormat('F Y') }}</span>
                    </div>
                    <button wire:click="nextMonth" class="p-2 hover:bg-white dark:hover:bg-gray-600 hover:shadow-sm rounded-md transition-all text-slate-500 dark:text-gray-400 hover:text-teal-600">
                        <x-heroicon-o-chevron-right class="w-5 h-5"/>
                    </button>
                </div>

                {{-- Search --}}
                <div class="relative w-full sm:w-72">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-5 h-5" />
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        placeholder="Cari nama pengemudi..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-sm placeholder:text-slate-400"
                    />
                </div>
            </div>

            {{-- Table Container --}}
            <div class="flex-1 overflow-auto relative no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow-sm">
                        <tr>
                            {{-- Sticky Column Header --}}
                            <th class="sticky left-0 z-20 bg-white dark:bg-gray-800 p-4 min-w-[220px] border-r border-slate-100 dark:border-gray-700 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)] border-b border-slate-200 dark:border-gray-700">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengemudi</span>
                            </th>
                            
                            {{-- Dates Header --}}
                            @php $daysInMonth = $this->currentDate->daysInMonth; @endphp
                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $date = $this->currentDate->copy()->day($day);
                                    $dayName = $date->locale('id')->translatedFormat('D');
                                    $isWeekend = $date->isWeekend();
                                    $isToday = $date->isToday();
                                @endphp
                                <th class="p-2 min-w-[64px] text-center border-r border-slate-50 dark:border-gray-700/50 border-b border-slate-200 dark:border-gray-700 last:border-r-0 bg-white dark:bg-gray-800 {{ $isWeekend ? 'bg-slate-50/50 dark:bg-gray-700/30' : '' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-[10px] font-medium text-slate-400 uppercase">{{ $dayName }}</span>
                                        <span @class([
                                            "text-sm font-bold w-8 h-8 flex items-center justify-center rounded-full transition-colors",
                                            "text-red-500 bg-red-50 dark:bg-red-500/10 dark:text-red-400" => $isWeekend,
                                            "text-slate-700 dark:text-gray-300" => !$isWeekend,
                                            "bg-teal-600 !text-white" => $isToday,
                                        ])>
                                            {{ $day }}
                                        </span>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse ($this->staf as $driver)
                            <tr class="group hover:bg-slate-50/80 dark:hover:bg-gray-700/50 transition-colors">
                                
                                {{-- Sticky Column Body --}}
                                <td class="sticky left-0 z-10 bg-white dark:bg-gray-800 group-hover:bg-slate-50/80 dark:group-hover:bg-gray-700/50 p-3 px-4 border-r border-slate-100 dark:border-gray-700 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)] transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div @class([
                                            "w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold border-2 border-white dark:border-gray-800 shadow-sm text-white",
                                            "bg-teal-500" => $loop->index % 4 === 0,
                                            "bg-blue-500" => $loop->index % 4 === 1,
                                            "bg-indigo-500" => $loop->index % 4 === 2,
                                            "bg-rose-500" => $loop->index % 4 === 3,
                                        ])>
                                            {{ \Illuminate\Support\Str::of($driver->nama_staf)->squish()->explode(' ')->map(fn($part) => $part[0] ?? '')->take(2)->implode('') }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-700 dark:text-gray-200 leading-tight">{{ $driver->nama_staf }}</div>
                                            <div class="text-[10px] text-slate-400">NIP: {{ $driver->nip_staf }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Date Cells --}}
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                     @php
                                        $currentCellDate = $this->currentDate->copy()->day($day)->startOfDay();
                                        $perjalanan = $driver->perjalanans->first(function ($p) use ($currentCellDate) {
                                            $start = \Carbon\Carbon::parse($p->waktu_keberangkatan)->startOfDay();
                                            $end = $p->waktu_kepulangan ? \Carbon\Carbon::parse($p->waktu_kepulangan)->endOfDay() : $start->copy()->endOfDay();
                                            return $currentCellDate->between($start, $end);
                                        });
                                        $isWeekend = $currentCellDate->isWeekend();
                                    @endphp
                                    <td class="p-1 border-r border-slate-50 dark:border-gray-700/50 last:border-r-0 h-16 relative transition-colors {{ $isWeekend ? 'bg-slate-50/30 dark:bg-gray-900/30' : '' }}">
                                        @if ($perjalanan)
                                            <div 
                                                onclick="$wire.dispatch('open-action-modal', { id: 'perjalananDetail', arguments: { perjalananId: {{ $perjalanan->id }} } })"
                                                class="absolute top-1 bottom-1 left-0.5 right-0.5 bg-teal-100/50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 rounded-md flex items-center justify-center group/cell cursor-pointer hover:bg-teal-100 dark:hover:bg-teal-500/20 transition-colors p-1"
                                            >
                                                <div class="text-center leading-tight">
                                                     <p class="text-[10px] font-bold text-teal-800 dark:text-teal-200 truncate">{{ $perjalanan->nama_kegiatan }}</p>
                                                     <p class="text-[9px] text-teal-600 dark:text-teal-400 truncate">{{ $perjalanan->alamat_tujuan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $daysInMonth + 1 }}" class="text-center p-12 text-gray-500 dark:text-gray-400">
                                    <x-heroicon-o-user-group class="w-12 h-12 mx-auto mb-4"/>
                                    Tidak ada data pengemudi yang ditemukan untuk filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Footer / Pagination hint --}}
            <div class="bg-slate-50 dark:bg-gray-800/50 p-3 border-t border-slate-200 dark:border-gray-700 text-xs text-slate-500 dark:text-gray-400 flex justify-between items-center">
                <span>Menampilkan {{ count($this->staf) }} pengemudi</span>
                {{-- Implementasi paginasi Livewire bisa ditambahkan di sini jika perlu --}}
            </div>
        </div>
    </div>
</x-filament-panels::page>