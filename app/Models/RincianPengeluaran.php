<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RincianPengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_pengeluaran_id',
        'perjalanan_id',
        'nomor_perjalanan',
        'nama_pengemudi',
        'waktu_keberangkatan',
        'alamat_tujuan',
        'nama_unit_kerja',
        'nopol_kendaraan',
        'biaya_parkir',
        'upload_bukti_parkir',
        'total',
    ];

    protected $casts = [
        'waktu_keberangkatan' => 'datetime',
    ];

    public function entryPengeluaran(): BelongsTo
    {
        return $this->belongsTo(EntryPengeluaran::class);
    }
}
