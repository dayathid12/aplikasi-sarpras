<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use App\Models\Wilayah;
use App\Models\Perjalanan;
use App\Models\UnitKerja;
use App\Models\Kegiatan;
use Closure;

class PeminjamanKendaraanUnpad extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.peminjaman-kendaraan-unpad';

    protected static ?string $title = 'Peminjaman Kendaraan Unpad';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    DateTimePicker::make('waktu_keberangkatan')->label('Waktu Keberangkatan')->required(),
                    DateTimePicker::make('waktu_kepulangan')->label('Waktu Kepulangan'),
                ]),
                TextInput::make('lokasi_keberangkatan')->label('Lokasi Keberangkatan')->required(),
                TextInput::make('jumlah_rombongan')->label('Jumlah Rombongan')->required()->numeric()->minValue(1),
                Select::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->options([
                        'Perjalanan Dinas' => 'Perjalanan Dinas',
                        'Kuliah Lapangan' => 'Kuliah Lapangan',
                        'Kunjungan Industri' => 'Kunjungan Industri',
                        'Kegiatan Perlombaan' => 'Kegiatan Perlombaan',
                        'Kegiatan Kemahasiswaan' => 'Kegiatan Kemahasiswaan',
                        'Kegiatan Perkuliahan' => 'Kegiatan Perkuliahan',
                        'Kegiatan Lainnya' => 'Kegiatan Lainnya',
                    ])->required(),
                Textarea::make('alamat_tujuan')->label('Alamat Tujuan')->required(),
                Select::make('unit_kerja_id')
                    ->label('Unit Kerja/Fakultas/UKM')
                    ->options(UnitKerja::all()->pluck('nama_unit_kerja', 'unit_kerja_id'))
                    ->searchable()
                    ->required(),
                TextInput::make('nama_pengguna')->label('Nama Pengguna')->required(),
                TextInput::make('kontak_pengguna')->label('Kontak Pengguna')->required(),
                Checkbox::make('use_same_info')
                    ->label('Gunakan informasi yang sama untuk Personil Perwakilan')
                    ->reactive()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state, Closure $get) {
                        if ($state) {
                            $set('nama_personil_perwakilan', $get('nama_pengguna'));
                            $set('kontak_pengguna_perwakilan', $get('kontak_pengguna'));
                        } else {
                            $set('nama_personil_perwakilan', null);
                            $set('kontak_pengguna_perwakilan', null);
                        }
                    }),
                TextInput::make('nama_personil_perwakilan')->label('Nama Personil Perwakilan')->required(),
                TextInput::make('kontak_pengguna_perwakilan')->label('Kontak Personil Perwakilan')->required(),
                Select::make('status_sebagai')
                    ->label('Status Sebagai')
                    ->options([
                        'Mahasiswa' => 'Mahasiswa',
                        'Dosen' => 'Dosen',
                        'Staf' => 'Staf',
                        'Lainnya' => 'Lainnya',
                    ])->required(),
                Select::make('tujuan_wilayah_id')
                    ->label('Kota Kabupaten')
                    ->options(Wilayah::all()->pluck('nama_wilayah', 'wilayah_id'))
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        if ($state) {
                            $wilayah = Wilayah::find($state);
                            if ($wilayah) {
                                $set('provinsi', $wilayah->provinsi);
                            }
                        } else {
                            $set('provinsi', null);
                        }
                    }),
                TextInput::make('provinsi')->label('Provinsi')->disabled(),
                Textarea::make('uraian_singkat_kegiatan')->label('Uraian Singkat Kegiatan'),
                Textarea::make('catatan_keterangan_tambahan')->label('Catatan/Keterangan Tambahan'),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        // Handle form submission here
        // For now, just a placeholder
        \Filament\Notifications\Notification::make()
            ->title('Form submitted (placeholder)!')
            ->success()
            ->send();
    }
}