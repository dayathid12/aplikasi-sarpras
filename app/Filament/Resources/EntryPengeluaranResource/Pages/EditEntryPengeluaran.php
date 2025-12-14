<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use App\Models\Staf; // Tambahkan ini
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class EditEntryPengeluaran extends EditRecord
{
    protected static string $resource = EntryPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('addPerjalanan')
                ->label('Tambahkan Perjalanan')
                ->form([
                    Select::make('pengemudi_id')
                        ->label('Nama Pengemudi')
                        ->options(function () {
                            $pengemudiIds = \App\Models\PerjalananKendaraan::whereHas('perjalanan', function (Builder $query) {
                                $query->whereIn('status_perjalanan', ['Terjadwal', 'Selesai']);
                            })
                            ->whereNotNull('pengemudi_id')
                            ->pluck('pengemudi_id')
                            ->unique();
                    
                            return Staf::whereIn('staf_id', $pengemudiIds)->pluck('nama_staf', 'staf_id');
                        })
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (\Filament\Forms\Set $set) {
                            $set('perjalanan_id', null);
                            $set('waktu_berangkat', null);
                            $set('alamat_tujuan', null);
                            $set('unit_kerja', null);
                            $set('nopol_kendaraan', null);
                        }),

                    Select::make('perjalanan_id')
                        ->label('Nomor Perjalanan')
                        ->options(function (\Filament\Forms\Get $get) {
                            $pengemudiId = $get('pengemudi_id');
                            if (!$pengemudiId) {
                                return [];
                            }
                        
                            $perjalananIds = \App\Models\PerjalananKendaraan::where('pengemudi_id', $pengemudiId)
                                ->whereHas('perjalanan', function (Builder $query) {
                                    $query->whereIn('status_perjalanan', ['Terjadwal', 'Selesai']);
                                })
                                ->pluck('perjalanan_id');
                        
                            return Perjalanan::whereIn('nomor_perjalanan', $perjalananIds)
                                ->get()
                                ->pluck('nomor_perjalanan', 'nomor_perjalanan');
                        })
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get, ?string $state) {
                            if (empty($state)) {
                                $set('waktu_berangkat', null);
                                $set('alamat_tujuan', null);
                                $set('unit_kerja', null);
                                $set('nopol_kendaraan', null);
                                return;
                            }
                        
                            $perjalanan = Perjalanan::with('unitKerja')->find($state);
                            if (!$perjalanan) {
                                return;
                            }
                        
                            $pengemudiId = $get('pengemudi_id');

                            $perjalananKendaraan = \App\Models\PerjalananKendaraan::where('perjalanan_id', $state)
                                ->where('pengemudi_id', $pengemudiId)
                                ->first();
                        
                            $set('waktu_berangkat', $perjalanan->waktu_keberangkatan?->format('d/m/Y H:i'));
                            $set('alamat_tujuan', $perjalanan->alamat_tujuan);
                            $set('unit_kerja', $perjalanan->unitKerja?->nama_unit_kerja);
                            $set('nopol_kendaraan', $perjalananKendaraan?->kendaraan_nopol);
                        }),
                    
                    TextInput::make('waktu_berangkat')
                        ->label('Waktu Berangkat')
                        ->disabled(),

                    TextInput::make('alamat_tujuan')
                        ->label('Alamat Tujuan')
                        ->disabled(),

                    TextInput::make('unit_kerja')
                        ->label('Unit Kerja/Fakultas/UKM')
                        ->disabled(),

                    TextInput::make('nopol_kendaraan')
                        ->label('Nomor Polisi Kendaraan')
                        ->disabled(),
                ])
                ->action(function (array $data) {
                    $entryPengeluaran = $this->record;
                    $perjalanan = Perjalanan::find($data['perjalanan_id']);

                    if ($perjalanan && $entryPengeluaran) {
                        // Here you should decide what to do. Do you want to associate one perjalanan
                        // to one entry_pengeluaran? If so, this is correct.
                        $perjalanan->entry_pengeluaran_id = $entryPengeluaran->id;
                        $perjalanan->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Perjalanan berhasil ditambahkan!')
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal menambahkan perjalanan.')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
