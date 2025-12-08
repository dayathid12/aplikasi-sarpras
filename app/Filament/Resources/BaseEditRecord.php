<?php

namespace App\Filament\Resources;

use Filament\Resources\Pages\EditRecord;

class BaseEditRecord extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
