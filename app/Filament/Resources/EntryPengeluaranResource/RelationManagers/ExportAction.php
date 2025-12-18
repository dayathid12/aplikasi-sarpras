<?php

namespace App\Filament\Resources\EntryPengeluaranResource\RelationManagers;

use pxlrbt\FilamentExcel\Actions\Tables\ExportAction as BaseExportAction;
use App\Exports\RincianBiayaExport;

class ExportAction extends BaseExportAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export Excel')
            ->icon('heroicon-o-document-arrow-down')
            ->color('success');
    }

    protected function getExport()
    {
        $ownerRecord = $this->getOwnerRecord();

        return RincianBiayaExport::make($ownerRecord)->withFilename('rincian_biaya_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }
}
