<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\Wilayah;
use Filament\Notifications\Notification;
use App\Models\Perjalanan; // Import the Perjalanan model
use Illuminate\Support\Facades\Log; // For logging

class PeminjamanKendaraanUnpad extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.peminjaman-kendaraan-unpad';

    protected static ?string $title = 'Peminjaman Kendaraan Unpad';

    // Disable navigation for this page as it will be accessed publicly
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('tujuan_wilayah_id')
                ->label('Kota Kabupaten')
                ->options(Wilayah::all()->pluck('nama_wilayah', 'wilayah_id')) // Assuming 'id' is the primary key for Wilayah
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
                })
                ->required(),
            TextInput::make('provinsi')
                ->label('Provinsi')
                ->disabled(),
            TextInput::make('nama_peminjam')
                ->label('Nama Peminjam')
                ->required(),
            TextInput::make('nidn_nip')
                ->label('NIDN/NIP')
                ->required(),
            TextInput::make('unit_kerja')
                ->label('Unit Kerja')
                ->required(),
            TextInput::make('no_telepon')
                ->label('Nomor Telepon')
                ->tel()
                ->required(),
            TextInput::make('tujuan')
                ->label('Tujuan Perjalanan')
                ->required(),
            TextInput::make('jumlah_penumpang')
                ->label('Jumlah Penumpang')
                ->numeric()
                ->required(),
            TextInput::make('tanggal_berangkat')
                ->label('Tanggal Berangkat')
                ->type('date')
                ->required(),
            TextInput::make('tanggal_kembali')
                ->label('Tanggal Kembali')
                ->type('date')
                ->required(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function submit(): void
    {
        try {
            $data = $this->form->getState();

            // Create a new Perjalanan record
            Perjalanan::create([
                'tujuan_wilayah_id' => $data['tujuan_wilayah_id'],
                'provinsi' => $data['provinsi'],
                'nama_peminjam' => $data['nama_peminjam'],
                'nidn_nip' => $data['nidn_nip'],
                'unit_kerja' => $data['unit_kerja'],
                'no_telepon' => $data['no_telepon'],
                'tujuan' => $data['tujuan'],
                'jumlah_penumpang' => $data['jumlah_penumpang'],
                'tanggal_berangkat' => $data['tanggal_berangkat'],
                'tanggal_kembali' => $data['tanggal_kembali'],
                // Add other fields from the Perjalanan model as necessary
                // 'status' => 'pending', // Example default status
            ]);

            Notification::make()
                ->title('Formulir berhasil dikirim!')
                ->success()
                ->send();

            $this->form->fill(); // Clear the form after successful submission

        } catch (\Throwable $e) {
            Log::error('Error submitting Peminjaman Kendaraan form: ' . $e->getMessage());
            Notification::make()
                ->title('Terjadi kesalahan saat mengirim formulir.')
                ->danger()
                ->body('Silakan coba lagi. Jika masalah berlanjut, hubungi administrator.')
                ->send();
        }
    }
}
