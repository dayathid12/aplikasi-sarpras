<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryPengeluaran extends Model
{
    use HasFactory;

    protected $fillable = ['nomor_berkas', 'nama_berkas'];
}
