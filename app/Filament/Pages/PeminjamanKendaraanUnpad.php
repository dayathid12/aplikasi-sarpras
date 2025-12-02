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
use Carbon\Carbon; // For date validation

class PeminjamanKendaraanUnpad extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.peminjaman-kendaraan-unpad';

    protected static ?string $title = 'Peminjaman Kendaraan Unpad';

    // Disable navigation for this page as it will be accessed publicly
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public int $currentStep = 1; // New property for multi-step form

    // Validation rules for each step
    protected function getValidationRulesForStep(int $step): array
    {
        switch ($step) {
            case 1:
                return [
                    'data.nama_peminjam' => ['required', 'string', 'max:255'],
                    'data.nidn_nip' => ['required', 'string', 'max:255'],
                    'data.unit_kerja' => ['required', 'string', 'max:255'],
                    'data.no_telepon' => ['required', 'string', 'max:20'],
                ];
            case 2:
                return [
                    'data.tujuan_wilayah_id' => ['required', 'exists:wilayahs,wilayah_id'],
                    'data.provinsi' => ['required', 'string', 'max:255'],
                    'data.tujuan' => ['required', 'string', 'max:255'],
                    'data.jumlah_penumpang' => ['required', 'numeric', 'min:1'],
                    'data.tanggal_berangkat' => ['required', 'date', 'after_or_equal:today'],
                    'data.tanggal_kembali' => ['required', 'date', 'after_or_equal:data.tanggal_berangkat'],
                ];
            default:
                return [];
        }
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getPersonalDetailsFormSchema(): array
    {
        return [
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
        ];
    }

    protected function getTripDetailsFormSchema(): array
    {
        return [
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
                })
                ->required(),
            TextInput::make('provinsi')
                ->label('Provinsi')
                ->disabled(),
            TextInput::make('tujuan')
                ->label('Tujuan Perjalanan')
                ->required(),
            TextInput::make('jumlah_penumpang')
                ->label('Jumlah Penumpang')
                ->numeric()
                ->required()
                ->minValue(1),
            TextInput::make('tanggal_berangkat')
                ->label('Tanggal Berangkat')
                ->type('date')
                ->required()
                ->minDate(now()->toDateString()),
            TextInput::make('tanggal_kembali')
                ->label('Tanggal Kembali')
                ->type('date')
                ->required()
                ->minDate(fn(\Filament\Forms\Get $get) => $get('tanggal_berangkat') ?? now()->toDateString()),
        ];
    }

    public function form(Form $form): Form
    {
        // Dynamically get the schema based on the current step
        $schema = match ($this->currentStep) {
            1 => $this->getPersonalDetailsFormSchema(),
            2 => $this->getTripDetailsFormSchema(),
            default => [], // Should not happen
        };

        return $form
            ->schema($schema)
            ->statePath('data');
    }

    public function nextStep(): void
    {
        // Validate current step fields
        $this->validateOnly(array_keys($this->getValidationRulesForStep($this->currentStep)));

        if ($this->currentStep < 2) { // Assuming 2 steps
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function finish(): void // Renamed from submit
    {
        // Validate all fields before final submission
        $this->validate($this->getValidationRulesForStep(1) + $this->getValidationRulesForStep(2));

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
                'tanggal_berangkat' => Carbon::parse($data['tanggal_berangkat']),
                'tanggal_kembali' => Carbon::parse($data['tanggal_kembali']),
                // Add other fields from the Perjalanan model as necessary
                // 'status' => 'pending', // Example default status
            ]);

            Notification::make()
                ->title('Formulir berhasil dikirim!')
                ->success()
                ->send();

            $this->form->fill(); // Clear the form after successful submission
            $this->currentStep = 1; // Reset to first step

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
