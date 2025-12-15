<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryPengeluaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entry_pengeluarans'; // Ganti dengan nama tabel Anda yang sebenarnya

    protected $fillable = ['nomor_berkas', 'nama_berkas'];

    public function perjalanans(): HasMany
    {
        return $this->hasMany(Perjalanan::class, 'entry_pengeluaran_id');
    }

    public function rincianPengeluarans(): HasMany
    {
        return $this->hasMany(RincianPengeluaran::class);
    }
}
