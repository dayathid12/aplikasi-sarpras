<style>
    /* Modern Scrollbar */
    .modern-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .modern-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .modern-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .dark .modern-scrollbar::-webkit-scrollbar-thumb {
        background-color: #475569;
    }

    /* Soft Glass Effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    .dark .glass-effect {
        background: rgba(17, 24, 39, 0.8);
    }
</style>

<x-filament-panels::page
    wire:sortable
    wire:end.stop="stopSorting"
    x-data="{
        selectedRecords: $wire.{{ $applySelectedRecordsFilterWithTableIdentifier('selectedRecords') }} || [],
        allRecordsSelected: false,
        selectAllRecords: function () {
            $wire.{{ $applySelectedRecordsFilterWithTableIdentifier('selectAllTableRecords') }}

            if (this.allRecordsSelected) {
                this.selectedRecords = []
            } else {
                this.selectedRecords = $wire.{{ $applySelectedRecordsFilterWithTableIdentifier('getAllTableRecordKeys') }}
            }
        },
    }"
    x-init="$watch('selectedRecords', value => $wire.{{ $applySelectedRecordsFilterWithTableIdentifier('setSelectedTableRecords') }})"
    @if ($pollingInterval = $getPollingInterval())
        wire:poll.{{ $pollingInterval }}
    @endif
>
    <div class="space-y-8">
        {{-- Header Actions with Modern Gradient --}}
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-200"></div>
                    <div class="relative p-3 bg-white dark:bg-gray-800 rounded-xl ring-1 ring-gray-900/5 shadow-sm">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Rincian Pengeluaran 2</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Monitoring data perjalanan & biaya</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @if (count($table->getHeaderActions()))
                    @foreach ($table->getHeaderActions() as $action)
                        {{ $action }}
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Filters Section --}}
        @if (count($table->getFilters()))
            <div class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-800/60 p-4 transition-all hover:bg-white dark:hover:bg-gray-900">
                <div class="flex flex-wrap gap-3">
                    @foreach ($table->getFilters() as $filter)
                        {{ $filter }}
                    @endforeach
                </div>
            </div>
        @endif

        {{-- MODERN STATISTICS CARDS --}}
        @php
            $totalBBM = $table->getRecords()->sum('total_bbm');
            $totalToll = $table->getRecords()->sum('total_toll');
            $totalParkir = $table->getRecords()->sum('total_parkir');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none border border-gray-100 dark:border-gray-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-blue-50 dark:bg-blue-900/20 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>

                <div class="relative flex flex-col h-full justify-between">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 uppercase tracking-wide">Fuel</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total BBM 2</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            <span class="text-sm align-top text-gray-400 font-normal mr-1">Rp</span>{{ number_format($totalBBM, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none border border-gray-100 dark:border-gray-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-emerald-50 dark:bg-emerald-900/20 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>

                <div class="relative flex flex-col h-full justify-between">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 uppercase tracking-wide">Toll</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Toll</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            <span class="text-sm align-top text-gray-400 font-normal mr-1">Rp</span>{{ number_format($totalToll, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none border border-gray-100 dark:border-gray-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-violet-50 dark:bg-violet-900/20 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>

                <div class="relative flex flex-col h-full justify-between">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-violet-50 dark:bg-violet-900/30 rounded-2xl group-hover:bg-violet-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-300 uppercase tracking-wide">Parking</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Parkir</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            <span class="text-sm align-top text-gray-400 font-normal mr-1">Rp</span>{{ number_format($totalParkir, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ULTRA MODERN TABLE --}}
        <div class="glass-effect rounded-3xl shadow-[0_0_50px_0_rgba(0,0,0,0.03)] border border-gray-200/50 dark:border-gray-800 overflow-hidden ring-1 ring-gray-900/5">
            {{-- Table Header --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-white/50 dark:bg-gray-800/50 flex items-center justify-between backdrop-blur-xl">
                <div class="flex items-center gap-3">
                    @if ($table->isSelectable())
                        <div class="relative flex items-center">
                            <input
                                type="checkbox"
                                wire:model.live="selectedRecords"
                                x-model="selectedRecords"
                                :value="$table->getAllTableRecordKeys()"
                                x-on:change="selectAllRecords()"
                                class="peer w-5 h-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-300 checked:bg-indigo-600 checked:border-indigo-600 dark:border-gray-600 dark:bg-gray-800 transition-all duration-200"
                            />
                            <svg class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none opacity-0 peer-checked:opacity-100 text-white transition-opacity" viewBox="0 0 14 14" fill="none">
                                <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                        {{ $table->getRecords()->count() }} Data ditemukan
                    </span>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto modern-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/50 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                        <tr>
                            @if ($table->isSelectable())
                                <th class="w-16 px-6 py-4 text-center">
                                    <span class="sr-only">Select</span>
                                </th>
                            @endif

                            @foreach ($table->getColumns() as $column)
                                <th class="px-6 py-4 {{ $column->isSortable() ? 'cursor-pointer hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors' : '' }}"
                                    @if ($column->isSortable()) wire:click="sortTable('{{ $column->getName() }}')" @endif>
                                    <div class="flex items-center gap-2">
                                        {{ $column->getLabel() }}
                                        @if ($column->isSortable())
                                            <svg class="w-4 h-4 transition-transform {{ $table->getSortColumn() === $column->getName() && $table->getSortDirection() === 'desc' ? 'rotate-180 text-indigo-500' : 'text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                            @endforeach

                            @if (count($table->getActions()) || count($table->getBulkActions()))
                                <th class="px-6 py-4 text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($table->getRecords() as $record)
                            <tr class="group hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-all duration-200">
                                @if ($table->isSelectable())
                                    <td class="px-6 py-4">
                                        <div class="relative flex items-center justify-center">
                                            <input
                                                type="checkbox"
                                                wire:model.live="selectedRecords"
                                                x-model="selectedRecords"
                                                :value="$record->getKey()"
                                                class="peer w-5 h-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-300 checked:bg-indigo-600 checked:border-indigo-600 dark:border-gray-600 dark:bg-gray-800 transition-all duration-200"
                                            />
                                            <svg class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none opacity-0 peer-checked:opacity-100 text-white transition-opacity" viewBox="0 0 14 14" fill="none">
                                                <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </td>
                                @endif

                                @foreach ($table->getColumns() as $column)
                                    <td class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                        {{ $column->getState($record) }}
                                    </td>
                                @endforeach

                                @if (count($table->getActions()) || count($table->getBulkActions()))
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            @foreach ($table->getActions() as $action)
                                                {{ $action }}
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-800">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tidak ada data</h3>
                                            <p class="text-gray-500 text-sm mt-1">Belum ada data pengeluaran yang tercatat.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Footer --}}
            @if ($table->isPaginated())
                <div class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 px-6 py-4">
                    {{ $table->getPagination() }}
                </div>
            @endif
        </div>

        {{-- Floating Bulk Actions --}}
        @if (count($table->getBulkActions()) && count($selectedRecords ?? []))
            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
                <div class="bg-gray-900/90 dark:bg-white/90 backdrop-blur-md text-white dark:text-gray-900 px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-6 border border-white/10 dark:border-gray-200 animate-in slide-in-from-bottom-5 duration-300">
                    <span class="text-sm font-medium">
                        <span class="font-bold">{{ count($selectedRecords) }}</span> terpilih
                    </span>
                    <div class="h-4 w-px bg-white/20 dark:bg-gray-900/20"></div>
                    <div class="flex gap-2">
                        @foreach ($table->getBulkActions() as $action)
                            {{ $action }}
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
