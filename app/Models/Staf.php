<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staf extends Model
{
    use HasFactory;

    protected $primaryKey = 'staf_id';

    protected $fillable = [
        'id_nama',
        'nama_staf',
        'gol_pangkat',
        'nip_staf',
        'status',
        'pendidikan_aktif',
        'wa_staf',
        'jabatan',
        'tanggal_lahir',
        'menuju_pensiun',
        'kartu_pegawai',
        'status_kepegawaian',
        'tempat_lahir',
        'no_ktp',
        'no_npwp',
        'no_bpjs_kesehatan',
        'no_bpjs_ketenagakerjaan',
        'no_telepon',
        'email',
        'alamat_rumah',
        'rekening',
        'nama_bank',
        'status_aplikasi',
    ];

    public function perjalanans()
    {
        return $this->belongsToMany(Perjalanan::class, 'perjalanan_kendaraans', 'pengemudi_id', 'perjalanan_id', 'staf_id', 'nomor_perjalanan')
                    ->withPivot('kendaraan_nopol', 'asisten_id');
    }

    /**
     * Get all of the jadwalPengemudis for the Staf
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jadwalPengemudis(): HasMany
    {
        return $this->hasMany(JadwalPengemudi::class, 'staf_id', 'staf_id');
    }
}

