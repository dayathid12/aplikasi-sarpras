<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use App\Filament\Resources\BaseEditRecord;
use Filament\Actions;
use Filament\Actions\Action;

class EditPerjalanan extends BaseEditRecord
{
    protected static string $resource = PerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('copyLink')
                ->label('Copy Link Pelacakan')
                ->view('filament.resources.perjalanan-resource.actions.copy-link', ['record' => $this->record]),
        ];
    }
}
