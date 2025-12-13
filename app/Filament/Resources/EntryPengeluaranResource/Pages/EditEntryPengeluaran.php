<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntryPengeluaran extends EditRecord
{
    protected static string $resource = EntryPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
