<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntryPengeluarans extends ListRecords
{
    protected static string $resource = EntryPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
