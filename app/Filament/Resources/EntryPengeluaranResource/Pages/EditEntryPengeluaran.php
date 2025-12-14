<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use App\Models\Staf; // Tambahkan ini
use App\Models\PerjalananKendaraan;
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
        $recordId = $this->record->id;

        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('addPerjalanan')
                ->label('Tambahkan Perjalanan')
                ->form([
                    Select::make('perjalanan_kendaraan_id')
                        ->label('Pilih Perjalanan')
                        ->options(function () use ($recordId) {
                            $perjalanans = Perjalanan::with(['wilayah', 'unitKerja', 'details.pengemudi', 'details.kendaraan'])
                                ->whereIn('status_perjalanan', ['Terjadwal', 'Selesai'])
                                ->where(function (Builder $query) use ($recordId) {
                                    $query->whereNull('entry_pengeluaran_id')
                                          ->orWhere('entry_pengeluaran_id', $recordId);
                                })
                                ->get();

                            $options = [];
                            foreach ($perjalanans as $perjalanan) {
                                foreach ($perjalanan->details as $detail) {
                                    $namaPengemudi = $detail->pengemudi?->nama_staf ?: 'N/A';
                                    $nomorPerjalanan = $perjalanan->nomor_perjalanan;
                                    $waktu = $perjalanan->waktu_keberangkatan?->format('d/m/y H:i') ?: 'N/A';
                                    $alamat = $perjalanan->alamat_tujuan ?: 'N/A';
                                    $kota = $perjalanan->wilayah?->nama_wilayah ?: 'N/A';
                                    $unitKerja = $perjalanan->unitKerja?->nama_unit_kerja ?: 'N/A';
                                    $nopol = $detail->kendaraan_nopol ?: 'N/A';
                                    
                                    $label = "{$namaPengemudi} | {$nomorPerjalanan} | {$nopol} | {$waktu} | {$alamat} | {$kota} | {$unitKerja}";
                                    
                                    // The key is the ID of the detail record, ensuring uniqueness
                                    $options[$detail->id] = $label;
                                }
                            }
                            return $options;
                        })
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (\Filament\Forms\Set $set, ?string $state) {
                            if (empty($state)) {
                                $set('nama_pengemudi', null);
                                $set('nomor_perjalanan', null);
                                $set('waktu_berangkat', null);
                                $set('alamat_tujuan', null);
                                $set('kota_kabupaten', null);
                                $set('unit_kerja', null);
                                $set('nopol_kendaraan', null);
                                return;
                            }

                            $detail = PerjalananKendaraan::with(['perjalanan.wilayah', 'perjalanan.unitKerja', 'pengemudi', 'kendaraan'])->find($state);
                            if (!$detail || !$detail->perjalanan) {
                                return;
                            }
                            
                            $perjalanan = $detail->perjalanan;

                            $set('nama_pengemudi', $detail->pengemudi?->nama_staf);
                            $set('nomor_perjalanan', $perjalanan->nomor_perjalanan);
                            $set('waktu_berangkat', $perjalanan->waktu_keberangkatan?->format('d/m/Y H:i'));
                            $set('alamat_tujuan', $perjalanan->alamat_tujuan);
                            $set('kota_kabupaten', $perjalanan->wilayah?->nama_wilayah);
                            $set('unit_kerja', $perjalanan->unitKerja?->nama_unit_kerja);
                            $set('nopol_kendaraan', $detail->kendaraan_nopol);
                        }),

                    TextInput::make('nama_pengemudi')
                        ->label('Nama Pengemudi')
                        ->disabled(),
                    TextInput::make('nomor_perjalanan')
                        ->label('Nomor Perjalanan')
                        ->disabled(),
                    TextInput::make('waktu_berangkat')
                        ->label('Waktu Berangkat')
                        ->disabled(),
                    TextInput::make('alamat_tujuan')
                        ->label('Alamat Tujuan')
                        ->disabled(),
                    TextInput::make('kota_kabupaten')
                        ->label('Kota Kabupaten')
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
                    $detail = PerjalananKendaraan::find($data['perjalanan_kendaraan_id']);

                    if ($detail && $entryPengeluaran) {
                        $perjalanan = $detail->perjalanan;
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
