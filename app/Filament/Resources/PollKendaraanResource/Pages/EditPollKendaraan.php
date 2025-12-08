<?php

namespace App\Filament\Resources\PollKendaraanResource\Pages;

use App\Filament\Resources\PollKendaraanResource;
use Filament\Actions;
use App\Filament\Resources\BaseEditRecord;

class EditPollKendaraan extends BaseEditRecord
{
    protected static string $resource = PollKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
