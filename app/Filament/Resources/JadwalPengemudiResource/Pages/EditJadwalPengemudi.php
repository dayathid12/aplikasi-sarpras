<?php

namespace App\Filament\Resources\JadwalPengemudiResource\Pages;

use App\Filament\Resources\JadwalPengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPengemudi extends EditRecord
{
    protected static string $resource = JadwalPengemudiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
