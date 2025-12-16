<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\EntryPengeluaran;
use App\Models\RincianPengeluaran;
use App\Models\RincianBiaya;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;

class ManageRincianBiayas extends Page implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable, \Filament\Infolists\Contracts\HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = EntryPengeluaranResource::class;

    protected static string $view = 'filament.resources.entry-pengeluaran-resource.pages.manage-rincian-biayas';

    public EntryPengeluaran $record;
    public RincianPengeluaran $rincianPengeluaran;

    public function mount(EntryPengeluaran $record, $rincianPengeluaranId): void
    {
        $this->record = $record;
        $this->rincianPengeluaran = RincianPengeluaran::with([
            'perjalananKendaraan.perjalanan.unitKerja',
            'perjalananKendaraan.perjalanan.wilayah',
            'perjalananKendaraan.pengemudi',
            'perjalananKendaraan.kendaraan'
        ])->findOrFail($rincianPengeluaranId);
    }
    
    public function infolist(Infolist $infolist): Infolist
    {
        $pk = $this->rincianPengeluaran->perjalananKendaraan;
        $perjalanan = $pk->perjalanan;

        return $infolist
            ->record($this->rincianPengeluaran)
            ->state([
                'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                'nama_pengemudi' => $pk->pengemudi->nama_staf ?? '-',
                'waktu_berangkat' => $perjalanan->waktu_keberangkatan->format('d M Y'),
                'alamat_tujuan' => $perjalanan->alamat_tujuan,
                'unit_kerja' => $perjalanan->unitKerja->nama_unit_kerja ?? '-',
                'nopol_kendaraan' => $pk->kendaraan->nopol_kendaraan ?? '-',
                'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? '-',
            ])
            ->schema([
                Section::make('Informasi Perjalanan')
                    ->description('Detail dari perjalanan yang terkait dengan rincian biaya ini.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nomor_perjalanan')->label('Nomor Perjalanan')->icon('heroicon-o-document-text'),
                        TextEntry::make('nama_pengemudi')->label('Nama Pengemudi')->icon('heroicon-o-user'),
                        TextEntry::make('waktu_berangkat')->label('Waktu Berangkat')->icon('heroicon-o-calendar-days'),
                        TextEntry::make('alamat_tujuan')->label('Alamat Tujuan')->columnSpan(2)->icon('heroicon-o-map-pin'),
                        TextEntry::make('unit_kerja')->label('Unit Kerja/Fakultas/UKM')->icon('heroicon-o-building-office-2'),
                        TextEntry::make('nopol_kendaraan')->label('Nomor Polisi Kendaraan')->icon('heroicon-o-truck'),
                        TextEntry::make('kota_kabupaten')->label('Kota/Kabupaten Tujuan')->icon('heroicon-o-map'),
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Tambah Biaya')
                ->label('Tambah Rincian Biaya')
                ->icon('heroicon-o-plus')
                ->action(function (array $data): void {
                    $this->rincianPengeluaran->rincianBiayas()->create($data);
                })
                ->form(fn(Form $form) => $this->getBiayaForm($form)),
        ];
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(RincianBiaya::where('rincian_pengeluaran_id', $this->rincianPengeluaran->id))
            ->columns([
                TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bbm' => 'success',
                        'toll' => 'warning',
                        'parkir' => 'info',
                    }),
                TextColumn::make('deskripsi')->searchable(),
                TextColumn::make('jenis_bbm')->label('Jenis BBM')->searchable(),
                TextColumn::make('volume')->suffix(' Ltr'),
                TextColumn::make('biaya')->money('IDR')->summarize(Sum::make()->label('Total Biaya')),
            ])
            ->actions([
                EditAction::make()->form(fn(Form $form) => $this->getBiayaForm($form)),
                DeleteAction::make(),
            ])
            ->headerActions([
                // Action is moved to getHeaderActions
            ])
            ->bulkActions([
                //
            ])
            ->striped();
    }

    private function getBiayaForm(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Tipe Biaya')->tabs([
                Tab::make('BBM')
                    ->icon('heroicon-o-beaker')
                    ->schema([
                        TextInput::make('tipe')->default('bbm')->hidden(),
                        TextInput::make('deskripsi')->label('Kode ATM/Keterangan'),
                        Select::make('jenis_bbm')->options(['Dexlite' => 'Dexlite', 'Pertamax' => 'Pertamax', 'Lainnya' => 'Lainnya'])->required(),
                        TextInput::make('volume')->label('Volume (Liter)')->numeric()->required(),
                        TextInput::make('biaya')->label('Total Biaya')->numeric()->prefix('Rp')->required(),
                        FileUpload::make('bukti_path')->label('Upload Struk BBM')->directory('struk-bbm'),
                    ]),
                Tab::make('Toll')
                    ->icon('heroicon-o-ticket')
                    ->schema([
                        TextInput::make('tipe')->default('toll')->hidden(),
                        TextInput::make('deskripsi')->label('Kode Kartu Toll/Gerbang')->required(),
                        TextInput::make('biaya')->label('Biaya Toll')->numeric()->prefix('Rp')->required(),
                        FileUpload::make('bukti_path')->label('Upload Struk Toll')->directory('struk-toll'),
                    ]),
                Tab::make('Parkir')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        TextInput::make('tipe')->default('parkir')->hidden(),
                        TextInput::make('deskripsi')->label('Lokasi Parkir')->required(),
                        TextInput::make('biaya')->label('Biaya Parkir')->numeric()->prefix('Rp')->required(),
                        FileUpload::make('bukti_path')->label('Upload Bukti Parkir')->directory('bukti-parkir'),
                    ]),
            ])->columnSpanFull(),
        ]);
    }
}
