<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPengemudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'staf_id',
        'tanggal_jadwal',
        'perjalanan_id',
        'keterangan',
    ];

    /**
     * Get the staf that owns the JadwalPengemudi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staf(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'staf_id', 'staf_id');
    }

    /**
     * Get the perjalanan that owns the JadwalPengemudi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function perjalanan(): BelongsTo
    {
        return $this->belongsTo(Perjalanan::class, 'perjalanan_id', 'nomor_perjalanan');
    }
}
