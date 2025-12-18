<?php

namespace App\Exports;

use App\Models\EntryPengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RincianBiayaExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $entryPengeluaran;
    public static $no = 1;

    public function __construct(EntryPengeluaran $entryPengeluaran)
    {
        $this->entryPengeluaran = $entryPengeluaran;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nomor Surat Jalan',
            'Waktu Keberangkatan',
            'Surtug',
            'Tanggal Kegiatan',
            'Kab / Kota',
            'Tujuan',
            'Kegiatan',
            'Unit Kerja/UKM',
            'Nopol Kendaraan',
            'Pengemudi',
            'Kode ATM',
            'Jenis BBM',
            'Volume (liter)',
            'Biaya BBM (Rp.)',
            'Kode Kartu Tol',
            'Biaya Tol (Rp.)',
            'Biaya Parkir/Lainnya (Rp.)',
        ];
    }

    public function collection()
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
            ->flatMap(function ($rincianPengeluaran) {
                return $rincianPengeluaran->rincianBiayas->map(function ($biaya) use ($rincianPengeluaran) {
                    $data = array_merge($rincianPengeluaran->toArray(), $biaya->toArray());
                    $data['perjalananKendaraan'] = $rincianPengeluaran->perjalananKendaraan;
                    return (object) $data;
                });
            });
    }

    public function map($row): array
    {
        static $no = 1;

        return [
            $no++,
            $row->nomor_perjalanan ?? '',
            '', // Surtug - not sure what this is
            $row->waktu_keberangkatan ? \Carbon\Carbon::parse($row->waktu_keberangkatan)->format('d/m/Y') : '',
            $row->kota_kabupaten ?? '',
            $row->alamat_tujuan ?? '',
            $row->perjalananKendaraan->perjalanan->nama_kegiatan ?? '',
            $row->nama_unit_kerja ?? '',
            $row->nopol_kendaraan ?? '',
            $row->nama_pengemudi ?? '',
            $row->tipe == 'bbm' ? ($row->deskripsi ?? '') : '',
            $row->tipe == 'bbm' ? ($row->jenis_bbm ?? '') : '',
            $row->tipe == 'bbm' ? ($row->volume ?? '') : '',
            $row->tipe == 'bbm' ? ($row->biaya ?? '') : '',
            $row->tipe == 'tol' ? ($row->deskripsi ?? '') : '',
            $row->tipe == 'tol' ? ($row->biaya ?? '') : '',
            $row->tipe == 'parkir_lainnya' ? ($row->biaya ?? '') : '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Insert Nama Berkas and Nomor Berkas at the top
                $sheet->insertNewRowBefore(1, 2);
                $sheet->setCellValue('A1', 'Nama Berkas');
                $sheet->setCellValue('A2', 'Nomor Berkas');

                // Set headings in row 3
                $headings = $this->headings();
                $col = 'A';
                foreach ($headings as $heading) {
                    $sheet->setCellValue($col . '3', $heading);
                    $col++;
                }

                // Set column widths or other formatting if needed
            },
        ];
    }
}
