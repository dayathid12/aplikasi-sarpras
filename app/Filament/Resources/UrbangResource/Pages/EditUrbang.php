<?php

namespace App\Filament\Resources\UrbangResource\Pages;

use App\Filament\Resources\UrbangResource;
use Filament\Actions;
use App\Filament\Resources\BaseEditRecord;

class EditUrbang extends BaseEditRecord
{
    protected static string $resource = UrbangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
