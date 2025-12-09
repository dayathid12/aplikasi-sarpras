<?php

namespace App\Filament\Resources\JadwalPengemudiResource\Pages;

use App\Filament\Resources\JadwalPengemudiResource;
use Filament\Resources\Pages\Page as FilamentPage; // Alias Page to avoid conflict
use App\Livewire\JadwalPengemudiCalendar; // Import the Livewire component

class ListJadwalPengemudis extends FilamentPage // Use FilamentPage as base class
{
    protected static string $resource = JadwalPengemudiResource::class;

    protected static ?string $title = 'Jadwal Pengemudi'; // Add a title for the page

    protected static string $view = 'filament.resources.jadwal-pengemudi-resource.pages.list-jadwal-pengemudis';

    public $currentDate;

    public function mount(): void
    {
        $this->currentDate = now()->format('Y-m-d');
    }
}
