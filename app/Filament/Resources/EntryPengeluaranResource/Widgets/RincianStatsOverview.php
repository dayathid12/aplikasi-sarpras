<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Widgets;

use App\Models\RincianBiaya;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RincianStatsOverview extends BaseWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        if (!$this->record) {
            return [];
        }

        // Get all rincian_pengeluaran_id's for the current entry
        $rincianPengeluaranIds = $this->record->rincianPengeluarans->pluck('id');

        // Calculate totals from RincianBiaya using a single, efficient query
        $biayaTotals = RincianBiaya::whereIn('rincian_pengeluaran_id', $rincianPengeluaranIds)
            ->select('tipe', DB::raw('SUM(biaya) as total'))
            ->groupBy('tipe')
            ->pluck('total', 'tipe');

        $totalBBM = $biayaTotals->get('bbm', 0);
        $totalToll = $biayaTotals->get('toll', 0);

        // Parkir cost is split between two tables, so we sum them.
        $totalParkirFromBiaya = $biayaTotals->get('parkir', 0);
        $totalParkirFromPengeluaran = $this->record->rincianPengeluarans->sum('biaya_parkir');
        $totalParkir = $totalParkirFromBiaya + $totalParkirFromPengeluaran;

        return [
            Stat::make('Total BBM', 'Rp ' . number_format($totalBBM, 0, ',', '.'))
                ->description('Total seluruh biaya BBM')
                ->color('success')
                ->icon('heroicon-o-funnel'),
            Stat::make('Total Toll', 'Rp ' . number_format($totalToll, 0, ',', '.'))
                ->description('Total seluruh biaya Toll')
                ->color('warning')
                ->icon('heroicon-o-ticket'),
            Stat::make('Total Parkir', 'Rp ' . number_format($totalParkir, 0, ',', '.'))
                ->description('Total seluruh biaya Parkir')
                ->color('info')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
