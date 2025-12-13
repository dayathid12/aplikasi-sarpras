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
                        ->options(Staf::all()->pluck('nama_staf', 'id'))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (\Filament\Forms\Set $set) {
                            $set('nomor_perjalanan', null);
                            $set('waktu_berangkat', null);
                            $set('alamat_tujuan', null);
                            $set('unit_kerja', null);
                            $set('nama_pengemudi', null); // Ini akan diisi nanti
                            $set('nopol_kendaraan', null); // Ini akan diisi nanti
                        }),

                                        Select::make('nomor_perjalanan')
                                            ->label('Nomor Perjalanan')
                                            ->options(function (\Filament\Forms\Get $get) {
                                                if (!$get('pengemudi_id')) {
                                                    return [];
                                                }
                                                // Mengambil Perjalanan yang terkait dengan pengemudi yang dipilih
                                                $perjalanans = Perjalanan::where('pengemudi_id', $get('pengemudi_id'))
                                                                            ->with(['pengemudi', 'kendaraan']) // Memuat relasi pengemudi dan kendaraan
                                                                            ->get();

                                                // Memformat opsi untuk menampilkan Nomor Perjalanan, Nama Pengemudi, dan Nomor Polisi Kendaraan
                                                return $perjalanans->mapWithKeys(function ($perjalanan) {
                                                    $namaPengemudi = $perjalanan->pengemudi ? $perjalanan->pengemudi->nama_staf : 'N/A';
                                                    $nopolKendaraan = $perjalanan->kendaraan ? $perjalanan->kendaraan->nopol_kendaraan : 'N/A';
                                                    return [$perjalanan->nomor_perjalanan => "{$perjalanan->nomor_perjalanan} - {$namaPengemudi} - {$nopolKendaraan}"];
                                                });
                                            })
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(function (\Filament\Forms\Set $set, ?string $state) {
                                                if ($state) {
                                                    $perjalanan = Perjalanan::with(['unitKerja', 'pengemudi', 'kendaraan'])
                                                                            ->where('nomor_perjalanan', $state)
                                                                            ->first();
                                                    if ($perjalanan) {
                                                        $set('waktu_berangkat', $perjalanan->waktu_keberangkatan);
                                                        $set('alamat_tujuan', $perjalanan->alamat_tujuan);
                                                        $set('unit_kerja', $perjalanan->unitKerja ? $perjalanan->unitKerja->nama_unit_kerja : 'N/A');
                                                        $set('nama_pengemudi', $perjalanan->pengemudi ? $perjalanan->pengemudi->nama_staf : 'N/A');
                                                        $set('nopol_kendaraan', $perjalanan->kendaraan ? $perjalanan->kendaraan->nopol_kendaraan : 'N/A');
                                                    } else {
                                                        $set('waktu_berangkat', null);
                                                        $set('alamat_tujuan', null);
                                                        $set('unit_kerja', null);
                                                        $set('nama_pengemudi', null);
                                                        $set('nopol_kendaraan', null);
                                                    }
                                                } else {
                                                    $set('waktu_berangkat', null);
                                                    $set('alamat_tujuan', null);
                                                    $set('unit_kerja', null);
                                                    $set('nama_pengemudi', null);
                                                    $set('nopol_kendaraan', null);
                                                }
                                            }),

                                        TextInput::make('nomor_perjalanan_display') // Field baru untuk Nomor Perjalanan
                                            ->label('Nomor Perjalanan')
                                            ->disabled()
                                            ->extraAttributes(['class' => 'font-bold text-primary-600']), // Opsional: untuk menonjolkan

                                        TextInput::make('waktu_berangkat')
                                            ->label('Waktu Berangkat')
                                            ->disabled(),

                                        TextInput::make('alamat_tujuan')
                                            ->label('Alamat Tujuan')
                                            ->disabled(),

                                        TextInput::make('unit_kerja')
                                            ->label('Unit Kerja/Fakultas/UKM')
                                            ->disabled(),

                                        TextInput::make('nama_pengemudi')
                                            ->label('Nama Pengemudi')
                                            ->disabled(),

                                        TextInput::make('nopol_kendaraan') // Field baru untuk Nomor Polisi Kendaraan
                                            ->label('Nomor Polisi Kendaraan')
                                            ->disabled(),                ])
                ->action(function (array $data) {
                    // Logic to attach the selected Perjalanan to the current EntryPengeluaran
                    $entryPengeluaran = $this->record;
                    $perjalanan = Perjalanan::where('nomor_perjalanan', $data['nomor_perjalanan'])->first();

                    if ($perjalanan && $entryPengeluaran) {
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
