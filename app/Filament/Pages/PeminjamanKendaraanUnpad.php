<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

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

    protected static bool $shouldSkipContentWrapper = true;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    public ?array $data = [];

    public $currentStep = 1;

    public function mount(): void
    {
        $this->form->fill();
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Perjalanan')
                    ->description('Lengkapi detail tentang waktu, lokasi, dan tujuan perjalanan Anda.')
                    ->icon('heroicon-o-map')
                    ->visible(fn () => $this->currentStep === 1)
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
                    ]),

                Section::make('Informasi Pengguna')
                    ->description('Masukkan data diri dan detail kontak penanggung jawab.')
                    ->icon('heroicon-o-user')
                    ->visible(fn () => $this->currentStep === 2)
                    ->schema([
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
                    ]),

                Section::make('Detail Perjalanan')
                    ->description('Sertakan detail tambahan tentang tujuan dan kegiatan perjalanan.')
                    ->icon('heroicon-o-pencil')
                    ->visible(fn () => $this->currentStep === 3)
                    ->schema([
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
                    ]),

                Section::make('Dokumen & Berkas')
                    ->description('Unggah dokumen-dokumen terkait perjalanan Anda (jika diperlukan).')
                    ->icon('heroicon-o-document-text')
                    ->visible(fn () => $this->currentStep === 4)
                    ->schema([
                        // Placeholder for document upload fields, if any
                        // For now, it will be an empty section
                    ]),
            ])
            ->statePath('data');
    }

    public function nextStep(): void
    {
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Mutate data before creation
        $data['jenis_kegiatan'] = $data['nama_kegiatan'];
        $data['nopol_kendaraan'] = null;
        $data['status_perjalanan'] = 'Permohonan';
        $data['jenis_operasional'] = 'Peminjaman';
        $data['status_operasional'] = 'Belum Ditetapkan';
        $data['pengemudi_id'] = null;

        Perjalanan::create($data);

        Notification::make()
            ->title('Permohonan berhasil diajukan!')
            ->success()
            ->send();

        $this->form->fill(); // Clear the form after submission
    }
}
