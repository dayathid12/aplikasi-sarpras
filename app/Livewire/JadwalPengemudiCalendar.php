<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Perjalanan;
use App\Models\Kendaraan;
use App\Models\PerjalananKendaraan;
use App\Models\JadwalPengemudi;

class JadwalPengemudiCalendar extends Component
{
    public $currentDate;
    public $search = '';

    public $showAssignDriverModal = false;
    public $assignDriverId;
    public $assignDate;
    public $keterangan = '';

    public array $manualSortOrder = [];

    public $showDeleteModal = false;
    public $driverToDeleteId;

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->assignDate = Carbon::now()->format('Y-m-d');
        
        // Initialize manualSortOrder from the database
        $this->manualSortOrder = Staf::orderBy('sort_order')
                                     ->orderBy('nama_staf')
                                     ->pluck('staf_id')
                                     ->toArray();

        // Ensure all staff have a sort_order. Assign sequential if null.
        $stafsWithoutOrder = Staf::whereNull('sort_order')->get();
        if ($stafsWithoutOrder->isNotEmpty()) {
            $maxOrder = Staf::max('sort_order') ?? 0;
            foreach ($stafsWithoutOrder as $staf) {
                $maxOrder++;
                $staf->sort_order = $maxOrder;
                $staf->save();
            }
            // Re-fetch manualSortOrder after assigning default values
            $this->manualSortOrder = Staf::orderBy('sort_order')
                                         ->orderBy('nama_staf')
                                         ->pluck('staf_id')
                                         ->toArray();
        }
    }

    public function openAssignDriverModal()
    {
        $this->resetAssignDriverForm();
        $this->showAssignDriverModal = true;
    }

    public function closeAssignDriverModal()
    {
        $this->showAssignDriverModal = false;
    }

    public function assignDriver()
    {
        $this->validate([
            'assignDriverId' => 'required|exists:stafs,staf_id',
            'assignDate' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        JadwalPengemudi::create([
            'staf_id' => $this->assignDriverId,
            'tanggal_jadwal' => $this->assignDate,
            'keterangan' => $this->keterangan,
        ]);

        $this->closeAssignDriverModal();
        $this->dispatch('notify', 'Pengemudi berhasil ditugaskan untuk tanggal ' . Carbon::parse($this->assignDate)->format('d M Y'))->to(JadwalPengemudiCalendar::class);
        $this->dispatch('refreshCalendar'); // To refresh the calendar data
    }

    private function resetAssignDriverForm()
    {
        $this->assignDriverId = null;
        $this->assignDate = Carbon::now()->format('Y-m-d');
        $this->keterangan = '';
    }

    public function previousMonth()
    {
        $this->currentDate->subMonth();
    }

    public function nextMonth()
    {
        $this->currentDate->addMonth();
    }

    public function deleteDriver($driverId)
    {
        $this->driverToDeleteId = $driverId;
        $this->showDeleteModal = true;
    }

    public function deleteConfirmed()
    {
        $driverId = $this->driverToDeleteId;
        $driver = Staf::find($driverId);

        if ($driver) {
            // Delete ALL associated JadwalPengemudi records for this driver, across all months.
            // The Staf record itself is NOT deleted.
            $driver->jadwalPengemudis()->delete();
            
            $this->dispatch('notify', 'Semua jadwal pengemudi berhasil dihapus.')->to(JadwalPengemudiCalendar::class);
            $this->dispatch('refreshCalendar');
        } else {
            $this->dispatch('notify', 'Pengemudi tidak ditemukan.')->to(JadwalPengemudiCalendar::class);
        }

        $this->showDeleteModal = false;
        $this->driverToDeleteId = null;
    }

    #[On('refreshCalendar')]
    public function refreshData()
    {
        // This method will be called when the 'refreshCalendar' event is dispatched.
        // Livewire will automatically re-render the component.
    }

    #[On('update-staf-sort')] // Listen for this event from the frontend
    public function updateSortOrder(array $newOrder)
    {
        foreach ($newOrder as $index => $stafId) {
            Staf::where('staf_id', $stafId)->update(['sort_order' => $index + 1]);
        }
        $this->manualSortOrder = $newOrder; // Update in-memory for immediate re-render
        $this->dispatch('notify', 'Urutan pengemudi berhasil diperbarui.')->to(JadwalPengemudiCalendar::class);
        $this->dispatch('refreshCalendar'); // Re-render with new order
    }

    public function getStafProperty()
    {
        $query = Staf::query();

        if ($this->search) {
            $query->where('nama_staf', 'like', '%' . $this->search . '%')
                  ->orWhere('nip_staf', 'like', '%' . $this->search . '%');
        }

        $query->orderBy('sort_order') // Primary sort by sort_order
              ->orderBy('nama_staf'); // Secondary sort for those without sort_order or new entries

        return $query->with(['jadwalPengemudis' => function ($query) {
                $query->whereYear('tanggal_jadwal', $this->currentDate->year)
                      ->whereMonth('tanggal_jadwal', $this->currentDate->month);
            }])
            ->get();
    }
    public function render()
    {
        return view('livewire.jadwal-pengemudi-calendar');
    }
}
