<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjalanan;

class PeminjamanController extends Controller
{
    public function success($token)
    {
        return view('filament.pages.peminjaman-kendaraan-unpad-success', compact('token'));
    }

    public function status($token)
    {
        $perjalanan = Perjalanan::where('token', $token)->first();

        return view('public-status', compact('perjalanan'));
    }

    public function form()
    {
        return redirect('/PeminjamanKendaraanUnpad');
    }
}
