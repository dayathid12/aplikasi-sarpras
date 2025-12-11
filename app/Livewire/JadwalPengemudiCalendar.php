<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Perjalanan; // Add Perjalanan model
// use App\Models\Kendaraan; // Kendaraan will be accessed via Perjalanan->kendaraan
// use App\Models\PerjalananKendaraan; // PerjalananKendaraan will be accessed via Perjalanan->details
// use App\Models\JadwalPengemudi; // JadwalPengemudi is no longer directly used

class JadwalPengemudiCalendar extends Component
{
    public $currentDate; // Keep for internal date manipulation if needed
    public $selectedMonth;
    public $selectedYear;
    public $selectedDriverId = ''; // For filtering by driver

    public $drivers = []; // Unique drivers with Perjalanan for the selected month/year
    public $allDrivers = []; // All staf for the driver filter dropdown
    public $dates = []; // Unique dates (days) in the selected month/year
    public $perjalanansByDriverAndDate = []; // Pivoted data

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

    public function updatedSelectedDriverId($value)
    {
        $this->selectedDriverId = $value;
        $this->loadPerjalananData();
    }

    public function loadPerjalananData()
    {
        $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->endOfDay();

        // Fetch Perjalanan records for the selected month/year
        $query = Perjalanan::query()
            ->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
            ->with(['pengemudi', 'kendaraan']); // Eager load pengemudi (Staf) and kendaraan

        $perjalanans = $query->get();

        // Filter and process data
        $this->drivers = collect();
        $this->dates = collect();
        $this->perjalanansByDriverAndDate = [];

        // Get all staf for the driver filter dropdown
        $this->allDrivers = Staf::orderBy('nama_staf')->get();

        foreach ($perjalanans as $perjalanan) {
            // Ensure Perjalanan has a pengemudi and kendaraan
            if ($perjalanan->pengemudi->isNotEmpty() && $perjalanan->kendaraan->isNotEmpty()) {
                foreach ($perjalanan->pengemudi as $pengemudi) {
                    $driverId = $pengemudi->staf_id;
                    $driverName = $pengemudi->nama_staf;
                    $date = Carbon::parse($perjalanan->waktu_keberangkatan)->format('Y-m-d'); // Date for column

                    // Apply driver filter if selected
                    if ($this->selectedDriverId && $this->selectedDriverId != $driverId) {
                        continue;
                    }

                    // Add driver to unique drivers list
                    if (!$this->drivers->contains('staf_id', $driverId)) {
                        $this->drivers->push(['staf_id' => $driverId, 'nama_staf' => $driverName]);
                    }

                    // Add date to unique dates list
                    if (!$this->dates->contains($date)) {
                        $this->dates->push($date);
                    }

                    // Store Perjalanan details for pivoting
                    if (!isset($this->perjalanansByDriverAndDate[$driverId][$date])) {
                        $this->perjalanansByDriverAndDate[$driverId][$date] = [];
                    }
                    $this->perjalanansByDriverAndDate[$driverId][$date][] = [
                        'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                        'merk_type' => $perjalanan->kendaraan->first()->merk_type ?? 'N/A',
                        'nopol_kendaraan' => $perjalanan->kendaraan->first()->nopol_kendaraan ?? 'N/A',
                        'waktu_keberangkatan' => Carbon::parse($perjalanan->waktu_keberangkatan)->format('d M Y H:i'),
                        'waktu_kepulangan' => Carbon::parse($perjalanan->waktu_kepulangan)->format('d M Y H:i'),
                        'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? $perjalanan->alamat_tujuan,
                    ];
                }
            }
        }
        // Sort drivers by name
        $this->drivers = $this->drivers->sortBy('nama_staf')->values();

        // Sort dates chronologically
        $this->dates = $this->dates->sort()->values();
    }

    public function render()
    {
        // Data is already loaded and processed in loadPerjalananData()
        // No need to re-fetch here, just pass to view
        $years = range(Carbon::now()->year - 5, Carbon::now()->year + 5); // Example: 5 years before and after

        return view('livewire.jadwal-pengemudi-calendar', [
            'years' => $years,
        ]);
    
}
}
