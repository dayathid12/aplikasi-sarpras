<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Kendaraan; // Changed from Staf
use App\Models\Perjalanan;

class BookingKendaraanCalendar extends Component
{
    public $currentDate;
    public $selectedMonth;
    public $selectedYear;
    public $search = '';
    public $selectedTipeTugas = 'Semua';

    public $vehicles = []; // Changed from $drivers
    public $dates = [];
    public $perjalanansByVehicleAndDate = []; // Changed from $perjalanansByDriverAndDate
    public $manualSortOrder = []; // New property for manual sorting

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedMonth = $this->currentDate->month;
        $this->selectedYear = $this->currentDate->year;
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

    public function updatedSearch()
    {
        $this->loadPerjalananData();
    }

    public function updatedSelectedTipeTugas($value)
    {
        $this->selectedTipeTugas = $value;
        $this->loadPerjalananData();
    }

    #[On('update-kendaraan-sort')]
    public function updateKendaraanSort($newOrder)
    {
        foreach ($newOrder as $index => $nopol) {
            Kendaraan::where('nopol_kendaraan', $nopol)->update(['sort_order' => $index]);
        }
        $this->manualSortOrder = $newOrder;
        $this->loadPerjalananData();
    }

    public function loadPerjalananData()
    {
        $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->endOfDay();

        // Populate $this->dates
        $this->dates = collect();
        $currentDay = $startOfMonth->copy();
        while ($currentDay->lte($endOfMonth)) {
            $this->dates->push($currentDay->format('Y-m-d'));
            $currentDay->addDay();
        }

        // Fetch and prepare vehicles
        $queriedVehicles = Kendaraan::query()
            ->when($this->search, function ($query) {
                $query->where('merk_type', 'like', '%' . $this->search . '%')
                      ->orWhere('nopol_kendaraan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('merk_type')
            ->orderBy('nopol_kendaraan')
            ->get();

        $this->vehicles = $queriedVehicles->map(fn ($v) => [
            'nopol_kendaraan' => $v->nopol_kendaraan,
            'merk_type' => $v->merk_type,
            'jenis_kendaraan' => $v->jenis_kendaraan,
        ])->values();

        $this->manualSortOrder = $queriedVehicles->pluck('nopol_kendaraan')->toArray();

        // Fetch Perjalanan records
        $perjalanansQuery = Perjalanan::query()
            ->whereIn('status_perjalanan', ['Terjadwal', 'Selesai'])
            ->with(['details.kendaraan', 'wilayah']); // Eager load relations from details

        // Dynamic date range filtering
        $perjalanansQuery->where(function ($query) use ($startOfMonth, $endOfMonth) {
            $query->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->whereHas('details', fn ($q) => $q->where('tipe_penugasan', 'Antar & Jemput'))
                    ->where('waktu_keberangkatan', '<', $endOfMonth)
                    ->where('waktu_kepulangan', '>', $startOfMonth);
            })->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->whereHas('details', fn ($q) => $q->where('tipe_penugasan', 'Antar (Keberangkatan)')->where('waktu_selesai_penugasan', '>', $startOfMonth))
                    ->where('waktu_keberangkatan', '<', $endOfMonth);
            })->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->whereHas('details', fn ($q) => $q->where('tipe_penugasan', 'Jemput (Kepulangan)')->where('waktu_selesai_penugasan', '>', $startOfMonth))
                    ->where('waktu_kepulangan', '<', $endOfMonth);
            });
        });

        // Filter by tipe_penugasan
        if ($this->selectedTipeTugas !== 'Semua') {
            $perjalanansQuery->whereHas('details', fn ($q) => $q->where('tipe_penugasan', $this->selectedTipeTugas));
        }

        $perjalanans = $perjalanansQuery->get();

        // Initialize data grid
        $this->perjalanansByVehicleAndDate = [];
        foreach ($this->vehicles as $vehicle) {
            $this->perjalanansByVehicleAndDate[$vehicle['nopol_kendaraan']] = [];
            foreach ($this->dates as $date) {
                $this->perjalanansByVehicleAndDate[$vehicle['nopol_kendaraan']][$date] = [];
            }
        }

        // Populate data grid
        foreach ($perjalanans as $perjalanan) {
            foreach ($perjalanan->details as $detail) {
                if (($this->selectedTipeTugas !== 'Semua' && $detail->tipe_penugasan !== $this->selectedTipeTugas) || !$detail->kendaraan) {
                    continue;
                }

                $startDate = null;
                $endDate = null;

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
                        $startDate = Carbon::parse($perjalanan->waktu_kepulangan);
                        $endDate = Carbon::parse($detail->waktu_selesai_penugasan);
                        break;
                }

                if (!$startDate || !$endDate || $endDate->lt($startDate)) {
                    continue;
                }
                
                $vehicleNopol = $detail->kendaraan->nopol_kendaraan;
                if (!isset($this->perjalanansByVehicleAndDate[$vehicleNopol])) {
                    continue;
                }

                $currentPerjalananDay = $startDate->copy()->startOfDay();
                while ($currentPerjalananDay->lte($endDate->copy()->endOfDay())) {
                    $dateKey = $currentPerjalananDay->format('Y-m-d');
                    if (isset($this->perjalanansByVehicleAndDate[$vehicleNopol][$dateKey])) {
                        $isAlreadyAdded = collect($this->perjalanansByVehicleAndDate[$vehicleNopol][$dateKey])->contains('id', $perjalanan->id);
                        if (!$isAlreadyAdded) {
                            $this->perjalanansByVehicleAndDate[$vehicleNopol][$dateKey][] = [
                                'id' => $perjalanan->id,
                                'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                                'merk_type' => $detail->kendaraan->merk_type ?? 'N/A',
                                'nopol_kendaraan' => $detail->kendaraan->nopol_kendaraan ?? 'N/A',
                                'waktu_keberangkatan' => $startDate->format('d M Y H:i'),
                                'waktu_kepulangan' => $endDate->format('d M Y H:i'),
                                'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? $perjalanan->alamat_tujuan,
                                'status_perjalanan' => $perjalanan->status_perjalanan,
                                'tipe_penugasan' => $detail->tipe_penugasan,
                            ];
                        }
                    }
                    $currentPerjalananDay->addDay();
                }
            }
        }
    }

    public function render()
    {
        $years = range(Carbon::now()->year - 5, Carbon::now()->year + 5);

        return view('livewire.booking-kendaraan-calendar', [
            'years' => $years,
        ]);
    }
}
