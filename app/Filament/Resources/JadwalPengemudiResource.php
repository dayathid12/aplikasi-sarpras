<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPengemudiResource\Pages;
use App\Models\JadwalPengemudi;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalPengemudiResource extends Resource
{
    protected static ?string $model = JadwalPengemudi::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Jadwal Pengemudi';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalPengemudis::route('/'),
        ];
    }
}
