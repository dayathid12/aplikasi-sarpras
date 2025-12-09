<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Perjalanan;

class JadwalPengemudiCalendar extends Component
{
    public $currentDate;
    public $search = '';

    public function mount()
    {
        $this->currentDate = Carbon::now();
    }

    public function previousMonth()
    {
        $this->currentDate->subMonth();
    }

    public function nextMonth()
    {
        $this->currentDate->addMonth();
    }

    public function getStafProperty()
    {
        $query = Staf::query();

        if ($this->search) {
            $query->where('nama_staf', 'like', '%' . $this->search . '%')
                  ->orWhere('nip_staf', 'like', '%' . $this->search . '%');
        }

        return $query->with(['perjalanans' => function ($query) {
                $query->whereYear('waktu_keberangkatan', $this->currentDate->year)
                      ->whereMonth('waktu_keberangkatan', $this->currentDate->month)
                      ->orWhere(function ($query) {
                            $query->whereYear('waktu_kepulangan', $this->currentDate->year)
                                  ->whereMonth('waktu_kepulangan', $this->currentDate->month);
                      });
            }])
            ->orderBy('nama_staf')
            ->get();
    }

    public function render()
    {
        return view('livewire.jadwal-pengemudi-calendar');
    }
}
