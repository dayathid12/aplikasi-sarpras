<?php

namespace App\Exports;

use App\Models\EntryPengeluaran;
use App\Models\RincianPengeluaran; // Import RincianPengeluaran model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection; // Import Collection class

class RincianBiayaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $entryPengeluaran;
    protected $nomorBerkas; // Will be set dynamically

    public function __construct(EntryPengeluaran $entryPengeluaran)
    {
        $this->entryPengeluaran = $entryPengeluaran;
        // Assuming nomor berkas comes from the first related EntryPengeluaran's Perjalanan
        $this->nomorBerkas = $entryPengeluaran->rincianPengeluarans->first()
                                ->perjalananKendaraan->perjalanan->nomor_perjalanan ?? 'N/A';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nomor Surat Jalan',
            'Kab / Kota',
            'Tujuan',
            'Kegiatan',
            'Unit Kerja/UKM',
            'Nopol Kendaraan',
            'Pengemudi',
            'Kode AT', 
            'Jenis BBM',
            'Volume (liter)',
            'Biaya BBM (Rp.)',
            'Kode Kartu Tol',
            'Biaya Tol (Rp.)',
            'Biaya Parkir/Lainnya (Rp.)',
        ];
    }

    public function collection(): Collection
    {
        return $this->entryPengeluaran->rincianPengeluarans()
            ->with([
                'perjalananKendaraan.perjalanan.unitKerja',
                'perjalananKendaraan.perjalanan.wilayah',
                'perjalananKendaraan.pengemudi',
                'perjalananKendaraan.kendaraan',
                'rincianBiayas'
            ])
            ->get()
            ->map(function ($rincianPengeluaran) {
                $totalBBM = 0;
                $totalTol = 0;
                $totalParkir = 0;
                $jenisBBM = '';
                $volumeBBM = '';
                $kodeKartuTol = '-'; // Default
                $kodeAT = 'KK'; // Default, as per user's hardcoded example

                foreach ($rincianPengeluaran->rincianBiayas as $biaya) {
                    if ($biaya->tipe === 'bbm') {
                        $totalBBM += $biaya->biaya;
                        $jenisBBM = $biaya->jenis_bbm;
                        $volumeBBM = $biaya->volume;
                    } elseif ($biaya->tipe === 'tol') {
                        $totalTol += $biaya->biaya;
                        // Assuming 'm' from user's example in map means some type of card
                        $kodeKartuTol = 'm'; 
                    } elseif ($biaya->tipe === 'parkir_lainnya') {
                        $totalParkir += $biaya->biaya;
                    }
                }

                // Attach aggregated data and relationships directly to the rincianPengeluaran object for easier mapping
                $rincianPengeluaran->aggregated_total_bbm = $totalBBM;
                $rincianPengeluaran->aggregated_jenis_bbm = $jenisBBM;
                $rincianPengeluaran->aggregated_volume_bbm = $volumeBBM;
                $rincianPengeluaran->aggregated_total_tol = $totalTol;
                $rincianPengeluaran->aggregated_kode_kartu_tol = $kodeKartuTol;
                $rincianPengeluaran->aggregated_total_parkir = $totalParkir;
                $rincianPengeluaran->aggregated_kode_at = $kodeAT;

                return $rincianPengeluaran;
            });
    }

    public function map($row): array
    {
        static $no = 1;

        $perjalanan = $row->perjalananKendaraan->perjalanan;
        $pengemudi = $row->perjalananKendaraan->pengemudi;
        $kendaraan = $row->perjalananKendaraan->kendaraan;
        $unitKerja = $perjalanan->unitKerja;
        $wilayah = $perjalanan->wilayah;

        return [
            $no++,
            $perjalanan->nomor_perjalanan ?? '',
            $wilayah->nama_wilayah ?? '',
            $perjalanan->alamat_tujuan ?? '',
            $perjalanan->nama_kegiatan ?? '',
            $unitKerja->nama_unit_kerja ?? '',
            $kendaraan->nopol_kendaraan ?? '',
            $pengemudi->nama_staf ?? '',
            $row->aggregated_kode_at, 
            $row->aggregated_jenis_bbm,
            $row->aggregated_volume_bbm,
            $row->aggregated_total_bbm,
            $row->aggregated_kode_kartu_tol,
            $row->aggregated_total_tol,
            $row->aggregated_total_parkir,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                
                // --- 1. SETUP JUDUL (HEADER ATAS) ---
                
                // Baris 1: Judul Utama
                $sheet->mergeCells('A1:O1');
                $sheet->setCellValue('A1', 'Tanda Terima SPJ BBM dan Tol Th. 2025');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'underline' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Baris 2: Nomor Berkas
                $sheet->mergeCells('A2:O2');
                $sheet->setCellValue('A2', 'Nomor Berkas: ' . $this->nomorBerkas);
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Baris 3: Tanggal (Rata Kanan)
                // Mengambil tanggal hari ini atau tanggal spesifik
                $tanggalCetak = \Carbon\Carbon::now()->translatedFormat('d F Y'); 
                $sheet->setCellValue('O3', $tanggalCetak);
                $sheet->getStyle('O3')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                ]);

                // --- 2. STYLING TABEL DATA ---
                
                // Mencari baris terakhir data
                $lastRow = $sheet->getHighestRow();
                
                // Style Header Tabel (Baris 4)
                $sheet->getStyle('A4:O4')->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Border untuk seluruh data (A4 sampai baris terakhir)
                $sheet->getStyle('A4:O' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);


                // --- 3. FOOTER (TANDA TANGAN & REKAPITULASI) ---
                
                $footerRow = $lastRow + 2; // Memberi jarak 1 baris kosong

                // Tanda Tangan Kiri
                $sheet->setCellValue('B' . $footerRow, 'Diserahkan Oleh:');
                
                // Tanda Tangan Tengah
                $sheet->setCellValue('D' . $footerRow, 'Diterima Oleh:');

                // --- REKAPITULASI (KANAN) ---
                // Layout disesuaikan dengan gambar:
                // Judul rekap ada di baris footerRow
                // Nilai rekap ada di baris footerRow + 1
                
                $rekapHeaderRow = $footerRow;
                $rekapValueRow = $footerRow + 1;

                // Label "Rekapitulasi" (Kolom K)
                $sheet->setCellValue('K' . $rekapHeaderRow, 'Rekapitulasi');
                $sheet->getStyle('K' . $rekapHeaderRow)->getFont()->setBold(true);
                $sheet->getStyle('K' . $rekapHeaderRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Header Kotak Rekapitulasi
                $sheet->setCellValue('L' . $rekapHeaderRow, 'BBM (Rp.)');
                $sheet->setCellValue('M' . $rekapHeaderRow, 'Biaya Tol (Rp.)');
                $sheet->setCellValue('N' . $rekapHeaderRow, 'Biaya Parkir/Lainnya (Rp.)'); // Perhatikan di gambar ini geser ke kiri (Kolom N, bukan O)

                // Styling Bold Header Rekap
                $sheet->getStyle('L' . $rekapHeaderRow . ':N' . $rekapHeaderRow)->getFont()->setBold(true);

                // Rumus SUM Total
                // Total BBM (Kolom L)
                $sheet->setCellValue('L' . $rekapValueRow, '=SUM(L5:L' . $lastRow . ')');
                
                // Total Tol (Kolom N di data, tapi ditaruh di M di rekap sesuai gambar layout visual)
                // Perhatikan: Data Tol ada di kolom N, tapi kotak rekap Tol ada di kolom M
                $sheet->setCellValue('M' . $rekapValueRow, '=SUM(N5:N' . $lastRow . ')');

                // Total Parkir (Kolom O di data, tapi ditaruh di N di rekap sesuai gambar layout visual)
                $sheet->setCellValue('N' . $rekapValueRow, '=SUM(O5:O' . $lastRow . ')');

                // Formatting Rupiah/Accounting (Opsional)
                $sheet->getStyle('L' . $rekapValueRow . ':N' . $rekapValueRow)
                      ->getNumberFormat()->setFormatCode('#,##0');

                // Border Kotak Rekapitulasi
                // Area: K(Header)-N(Value) sesuai visual
                $sheet->getStyle('K' . $rekapHeaderRow . ':N' . $rekapValueRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            },
        ];
    }
}