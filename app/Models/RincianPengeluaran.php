<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'kota_kabupaten',
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

    public function perjalananKendaraan(): BelongsTo
    {
        return $this->belongsTo(PerjalananKendaraan::class, 'perjalanan_id');
    }

    public function rincianBiayas(): HasMany
    {
        return $this->hasMany(RincianBiaya::class);
    }
}
