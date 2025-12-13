<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\EntryPengeluaran;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateEntryPengeluaran extends CreateRecord
{
    protected static string $resource = EntryPengeluaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $currentDate = Carbon::now();
        $dd = $currentDate->format('d');
        $yy = $currentDate->format('y');

        // Find the highest existing sequence number for today's date and year
        $latestEntry = EntryPengeluaran::where('nomor_berkas', 'like', $dd . $yy . '-%')
                                        ->orderByDesc('nomor_berkas')
                                        ->first();

        $sequence = 1;
        if ($latestEntry) {
            $parts = explode('-', $latestEntry->nomor_berkas);
            if (count($parts) > 1) {
                $sequence = (int) end($parts) + 1;
            }
        }

        $data['nomor_berkas'] = $dd . $yy . '-' . $sequence;

        return $data;
    }
}
