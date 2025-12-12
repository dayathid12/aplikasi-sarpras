<?php

namespace App\Filament\Resources\BookingKendaraanResource\Pages;

use App\Filament\Resources\BookingKendaraanResource;
use Filament\Resources\Pages\Page as FilamentPage; // Alias Page to avoid conflict
use App\Livewire\BookingKendaraanCalendar; // Import the Livewire component
use Filament\Support\Enums\MaxWidth;

class ListBookingKendaraans extends FilamentPage // Use FilamentPage as base class
{
    protected static string $resource = BookingKendaraanResource::class;

    protected static ?string $title = 'Booking Kendaraan'; // Add a title for the page

    protected static string $view = 'filament.resources.booking-kendaraan-resource.pages.list-booking-kendaraans';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}