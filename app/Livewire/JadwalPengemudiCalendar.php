<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Perjalanan; // Add Perjalanan model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use App\Models\Kendaraan; // Kendaraan will be accessed via Perjalanan->kendaraan
// use App\Models\PerjalananKendaraan; // PerjalananKendaraan will be accessed via Perjalanan->details
// use App\Models\JadwalPengemudi; // JadwalPengemudi is no longer directly used

class JadwalPengemudiCalendar extends Component
{
    public $currentDate; // Keep for internal date manipulation if needed
    public $selectedMonth;
    public $selectedYear;
    public $search = ''; // Added this line
    public $selectedTipeTugas = 'Semua'; // Filter by task type

    public $drivers = []; // All drivers
    public $dates = []; // All dates in the selected month/year
    public $perjalanansByDriverAndDate = []; // Pivoted data

    public $manualSortOrder = []; // New property for manual sorting

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedMonth = $this->currentDate->month;
        $this->selectedYear = $this->currentDate->year;
        $this->loadPerjalananData();
    }

    #[On('update-staf-sort')]
    public function updateStafSort($newOrder)
    {
        foreach ($newOrder as $index => $stafId) {
            Staf::where('staf_id', $stafId)->update(['sort_order' => $index]);
        }
        $this->manualSortOrder = $newOrder;
        $this->loadPerjalananData();
    }

    public function updatedSelectedMonth($value)
    {
        $this->selectedMonth = $value;
        $this->loadPerjalananData();
    }

    public function updatedSelectedYear($value)
    {
        $this->selectedYear = $value;
        $this->loadPerjalananData();
    }

    public function updatedSelectedTipeTugas($value)
    {
        $this->selectedTipeTugas = $value;
        $this->loadPerjalananData();
    }

    public function updatedSearch()
    {
        $this->loadPerjalananData();
    }

    public function loadPerjalananData()
    {
        $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->endOfDay();

        // Populate $this->dates with all days of the month
        $this->dates = collect();
        $currentDay = $startOfMonth->copy();
        while ($currentDay->lte($endOfMonth)) {
            $this->dates->push($currentDay->format('Y-m-d'));
            $currentDay->addDay();
        }

        // Fetch all drivers, filtered by search term
        $queriedDrivers = Staf::query()
            ->when($this->search, function ($query) {
                $query->where('nama_staf', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('nama_staf')
            ->get();

        $this->drivers = $queriedDrivers
            ->map(function ($staf) {
                return ['staf_id' => $staf->staf_id, 'nama_staf' => $staf->nama_staf, 'nip' => $staf->nip_staf];
            })->values();

        $this->manualSortOrder = $queriedDrivers->pluck('staf_id')->toArray();

        // Fetch Perjalanan records for the selected month/year
        $perjalanansQuery = Perjalanan::query()
            ->whereIn('status_perjalanan', ['Terjadwal', 'Selesai'])
            ->with(['details.pengemudi', 'details.kendaraan', 'wilayah']); // Eager load relations from details

        // Dynamic date range filtering based on selectedTipeTugas
        $perjalanansQuery->where(function ($query) use ($startOfMonth, $endOfMonth) {
            // Case 1: Overlaps with 'Antar & Jemput'
            $query->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->whereHas('details', fn ($q) => $q->where('tipe_penugasan', 'Antar & Jemput'))
                    ->where('waktu_keberangkatan', '<', $endOfMonth)
                    ->where('waktu_kepulangan', '>', $startOfMonth);
            });
            // Case 2: Overlaps with 'Antar (Keberangkatan)'
            $query->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->whereHas('details', function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('tipe_penugasan', 'Antar (Keberangkatan)')
                      ->where('waktu_selesai_penugasan', '>', $startOfMonth);
                })->where('waktu_keberangkatan', '<', $endOfMonth);
            });
            // Case 3: Overlaps with 'Jemput (Kepulangan)'
            $query->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->whereHas('details', function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('tipe_penugasan', 'Jemput (Kepulangan)')
                      ->where('waktu_selesai_penugasan', '>', $startOfMonth);
                })->where('waktu_kepulangan', '<', $endOfMonth);
            });
        });

        // Add filter for tipe_penugasan if it's not 'Semua'
        if ($this->selectedTipeTugas !== 'Semua') {
            $perjalanansQuery->whereHas('details', function ($query) {
                $query->where('tipe_penugasan', $this->selectedTipeTugas);
            });
        }

        $perjalanans = $perjalanansQuery->get();

        // Initialize perjalanansByDriverAndDate
        $this->perjalanansByDriverAndDate = [];
        foreach ($this->drivers as $driver) {
            foreach ($this->dates as $date) {
                $this->perjalanansByDriverAndDate[$driver['staf_id']][$date] = [];
            }
        }

        // Populate perjalanansByDriverAndDate
        foreach ($perjalanans as $perjalanan) {
            foreach ($perjalanan->details as $detail) {

                // Skip if the selected filter doesn't match this detail's type
                if ($this->selectedTipeTugas !== 'Semua' && $detail->tipe_penugasan !== $this->selectedTipeTugas) {
                    continue;
                }

                $startDate = null;
                $endDate = null;

                // Determine start and end date based on tipe_penugasan
                switch ($detail->tipe_penugasan) {
                    case 'Antar & Jemput':
                        $startDate = Carbon::parse($perjalanan->waktu_keberangkatan);
                        $endDate = Carbon::parse($perjalanan->waktu_kepulangan);
                        break;
                    case 'Antar (Keberangkatan)':
                        $startDate = Carbon::parse($perjalanan->waktu_keberangkatan);
                        $endDate = Carbon::parse($detail->waktu_selesai_penugasan);
                        break;
                    case 'Jemput (Kepulangan)':
                        // For 'Jemput', the start is 'waktu_kepulangan' from the parent
                        $startDate = Carbon::parse($perjalanan->waktu_kepulangan);
                        $endDate = Carbon::parse($detail->waktu_selesai_penugasan);
                        break;
                }

                if (!$startDate || !$endDate || $endDate->lt($startDate)) {
                    continue; // Skip if dates are invalid
                }

                $driversForDetail = collect([$detail->pengemudi, $detail->asisten])->filter();

                foreach ($driversForDetail as $pengemudi) {
                    if (!$pengemudi) continue;

                    $driverId = $pengemudi->staf_id;
                    if (!isset($this->perjalanansByDriverAndDate[$driverId])) {
                        continue; // Skip if driver is not in the current filtered/searched list
                    }

                    $currentPerjalananDay = $startDate->copy()->startOfDay();
                    while ($currentPerjalananDay->lte($endDate->copy()->endOfDay())) {
                        $dateKey = $currentPerjalananDay->format('Y-m-d');

                        if (isset($this->perjalanansByDriverAndDate[$driverId][$dateKey])) {
                            // Prevent duplicate entries for the same trip on the same day
                            $isAlreadyAdded = collect($this->perjalanansByDriverAndDate[$driverId][$dateKey])
                                ->contains('id', $perjalanan->id);

                            if (!$isAlreadyAdded) {
                                $this->perjalanansByDriverAndDate[$driverId][$dateKey][] = [
                                    'id' => $perjalanan->id,
                                    'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                                    'merk_type' => $detail->kendaraan->merk_type ?? 'N/A',
                                    'nopol_kendaraan' => $detail->kendaraan->nopol_kendaraan ?? 'N/A',
                                    'waktu_keberangkatan' => $startDate->format('d M Y H:i'),
                                    'waktu_kepulangan' => $endDate->format('d M Y H:i'),
                                    'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? $perjalanan->alamat_tujuan,
                                    'tipe_penugasan' => $detail->tipe_penugasan, // Pass for debugging or display
                                ];
                            }
                        }
                        $currentPerjalananDay->addDay();
                    }
                }
            }
        }
    }

    public function render()
    {
        $years = range(Carbon::now()->year - 5, Carbon::now()->year + 5);

        return view('livewire.jadwal-pengemudi-calendar', [
            'years' => $years,
        ]);

}
}
