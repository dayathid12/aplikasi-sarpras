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

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->assignDate = Carbon::now()->format('Y-m-d');
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

    #[On('refreshCalendar')]
    public function refreshData()
    {
        // This method will be called when the 'refreshCalendar' event is dispatched.
        // Livewire will automatically re-render the component.
    }

    public function getStafProperty()
    {
        $query = Staf::query();

        if ($this->search) {
            $query->where('nama_staf', 'like', '%' . $this->search . '%')
                  ->orWhere('nip_staf', 'like', '%' . $this->search . '%');
        }

        return $query->with(['jadwalPengemudis' => function ($query) {
                $query->whereYear('tanggal_jadwal', $this->currentDate->year)
                      ->whereMonth('tanggal_jadwal', $this->currentDate->month);
            }])
            ->orderBy('nama_staf')
            ->get();
    }

    public function render()
    {
        return view('livewire.jadwal-pengemudi-calendar');
    }
}
