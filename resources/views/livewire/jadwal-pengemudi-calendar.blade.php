<div class="w-full">
    <div class="font-sans w-full">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Jadwal Pengemudi</h2>
                <p class="text-slate-500 dark:text-gray-400 mt-1 text-sm">Kelola penugasan pengemudi harian Anda di sini.</p>
            </div>
            {{-- Tombol di header bisa diaktifkan jika perlu --}}
            <button class="bg-teal-600 hover:bg-teal-700 active:scale-95 transform transition-all text-white px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md text-sm font-medium flex items-center gap-2">
                <span>Perjalanan Detail</span>
            </button>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden flex flex-col h-[calc(100vh-220px)] min-h-[500px] w-full">

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
                                <div class="flex items-center gap-2">
                                    <div class="relative w-full sm:w-72">
                                        <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none w-5 h-5" />
                                        <input
                                            wire:model.live.debounce.300ms="search"
                                            type="text"
                                            placeholder="Cari nama pengemudi..."
                                            class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-sm placeholder:text-slate-400"
                                        />
                                    </div>
                                    <button
                                        wire:click="openAssignDriverModal"
                                        class="p-2.5 bg-teal-600 hover:bg-teal-700 active:scale-95 transform transition-all text-white rounded-lg shadow-sm hover:shadow-md text-sm font-medium flex items-center justify-center flex-shrink-0"
                                        title="Tambahkan Pengemudi"
                                    >
                                        <x-heroicon-o-user-plus class="w-5 h-5" />
                                    </button>
                                </div>            </div>

            {{-- Table Container --}}
            <div x-data="{
                stafOrder: @entangle('manualSortOrder'),
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
            }" class="flex-grow overflow-hidden relative">
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
                    <tbody x-ref="stafTableBody" class="divide-y divide-slate-100 dark:divide-gray-700">

                        @forelse ($this->staf as $driver)
                            <tr class="group hover:bg-slate-50/80 dark:hover:bg-gray-700/50 transition-colors" wire:key="{{ $driver->staf_id }}" data-staf-id="{{ $driver->staf_id }}">

                                {{-- Sticky Column Body --}}
                                <td class="sticky left-0 z-10 bg-white dark:bg-gray-800 group-hover:bg-slate-50/80 dark:group-hover:bg-gray-700/50 p-3 px-4 border-r border-slate-100 dark:border-gray-700 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="drag-handle p-0.5 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 cursor-grab rounded-full">
                                            <x-heroicon-o-bars-3 class="w-3 h-3" />
                                        </button>
                                        <button wire:click="deleteDriver({{ $driver->staf_id }})" type="button" class="p-0.5 text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition-colors rounded-full">
                                            <x-heroicon-o-trash class="w-3 h-3" />
                                        </button>
                                        <div class="flex flex-col">
                                            <div class="text-sm font-semibold text-slate-700 dark:text-gray-200 leading-tight whitespace-nowrap overflow-hidden text-ellipsis">{{ $driver->nama_staf }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $driver->nip_staf }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Date Cells --}}
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $currentCellDate = $this->currentDate->copy()->day($day)->startOfDay();
                                        $jadwal = $driver->jadwalPengemudis->first(function ($j) use ($currentCellDate) {
                                            return \Carbon\Carbon::parse($j->tanggal_jadwal)->startOfDay()->eq($currentCellDate);
                                        });
                                        $isWeekend = $currentCellDate->isWeekend();
                                    @endphp
                                    <td class="p-1 border-r border-slate-50 dark:border-gray-700/50 last:border-r-0 h-16 relative transition-colors {{ $isWeekend ? 'bg-slate-50/30 dark:bg-gray-900/30' : '' }}">
                                        @if ($jadwal)
                                            <div
                                                class="absolute top-1 bottom-1 left-0.5 right-0.5 bg-teal-100/50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 rounded-md flex items-center justify-center group/cell cursor-pointer hover:bg-teal-100 dark:hover:bg-teal-500/20 transition-colors p-1"
                                                title="{{ $jadwal->keterangan ?? 'Penugasan' }}"
                                            >
                                                <div class="text-center leading-tight">
                                                     <p class="text-[10px] font-bold text-teal-800 dark:text-teal-200 truncate">{{ $jadwal->keterangan ?? 'Penugasan' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endfor                            </tr>
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

                        {{-- Footer / Pagination hint --}}
                        <div class="bg-slate-50 dark:bg-gray-800/50 p-3 border-t border-slate-200 dark:border-gray-700 text-xs text-slate-500 dark:text-gray-400 flex justify-between items-center">
                            <span>Menampilkan {{ count($this->staf) }} pengemudi</span>
                            {{-- Implementasi paginasi Livewire bisa ditambahkan di sini jika perlu --}}
                        </div>
                    </div>
                </div>

                {{-- Assign Driver Modal --}}
                            <div
                                x-data="{ show: @entangle('showAssignDriverModal') }"
                                x-show="show"
                                x-on:keydown.escape.window="show = false"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                style="display: none;"
                            >
                                <div x-on:click="show = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                
                                <div
                                    x-show="show"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6"
                                >
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tambahkan Pengemudi</h3>
                                        <button x-on:click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <x-heroicon-o-x-mark class="w-6 h-6" />
                                        </button>
                                    </div>
                
                                    <form wire:submit.prevent="assignDriver">
                                        <div class="space-y-4">
                                            <div>
                                                <label for="assignDriverId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pengemudi</label>
                                                <select
                                                    wire:model="assignDriverId"
                                                    id="assignDriverId"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                >
                                                    <option value="">Pilih Pengemudi</option>
                                                    @foreach (\App\Models\Staf::orderBy('nama_staf')->get() as $staf)
                                                        <option value="{{ $staf->staf_id }}">{{ $staf->nama_staf }}</option>
                                                    @endforeach
                                                </select>
                                                @error('assignDriverId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                
                                            <div>
                                                <label for="assignDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                                                <input
                                                    wire:model="assignDate"
                                                    type="date"
                                                    id="assignDate"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                />
                                                @error('assignDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                
                                            <div>
                                                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan (Opsional)</label>
                                                <textarea
                                                    wire:model="keterangan"
                                                    id="keterangan"
                                                    rows="3"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                ></textarea>
                                                @error('keterangan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" x-on:click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                
                            {{-- Delete Confirmation Modal --}}
                            <div
                                x-data="{ show: @entangle('showDeleteModal') }"
                                x-show="show"
                                x-on:keydown.escape.window="show = false"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                style="display: none;"
                            >
                                <div x-on:click="show = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                
                                <div
                                    x-show="show"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6"
                                >
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Penghapusan Jadwal</h3>
                                        <button x-on:click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <x-heroicon-o-x-mark class="w-6 h-6" />
                                        </button>
                                    </div>
                
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                        Apakah Anda yakin ingin menghapus semua jadwal untuk pengemudi ini? Tindakan ini akan menghapus semua jadwal di semua bulan dan tidak dapat dibatalkan. Data pengemudi itu sendiri tidak akan dihapus.
                                    </div>
                
                                    <div class="flex justify-end gap-3">
                                        <button type="button" x-on:click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                                            Batal
                                        </button>
                                        <button type="button" wire:click="deleteConfirmed()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" style="display: block !important;">
                                            Ya, Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>            </div>

        </div>
