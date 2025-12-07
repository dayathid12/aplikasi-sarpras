<?php

namespace App\Http\Controllers;

use App\Models\Perjalanan;
use App\Models\Wilayah;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Added this line

class PeminjamanKendaraanController extends Controller
{
    /**
     * Show the peminjaman kendaraan form
     */
    public function show()
    {
        $wilayahs = Wilayah::all();
        $unitKerjas = UnitKerja::all();

        return view('peminjaman-kendaraan-v2', [
            'wilayahs' => $wilayahs,
            'unitKerjas' => $unitKerjas,
        ]);
    }

    /**
     * Submit the form and save to database
     */
    public function submit(Request $request)
    {
        // Validation for both regular fields and files
        $rules = [
            'waktu_keberangkatan' => 'required|date',
            'lokasi_keberangkatan' => 'required|string|max:255',
            'jumlah_rombongan' => 'required|numeric|min:1',
            'alamat_tujuan' => 'required|string|max:500',
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan_wilayah_id' => 'required|exists:wilayahs,wilayah_id',
            'unit_kerja_id' => 'required|exists:unit_kerjas,unit_kerja_id',
            'nama_pengguna' => 'required|string|max:255',
            'kontak_pengguna' => 'required|string|max:20',
            'nama_personil_perwakilan' => 'required|string|max:255',
            'kontak_pengguna_perwakilan' => 'required|string|max:20',
            'status_sebagai' => 'required|in:Mahasiswa,Dosen,Staf,Lainnya',
            'surat_peminjaman' => 'required|file|mimes:pdf,jpg,png|max:5120', // 5MB max
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB max
            'provinsi' => 'nullable|string|max:255',
            'uraian_singkat_kegiatan' => 'nullable|string',
            'catatan_keterangan_tambahan' => 'nullable|string|max:255',
        ];

        // Perform validation directly on the request
        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            // Generate unique token/UUID
            $token = Str::uuid()->toString();

            // Handle file uploads
            $suratPeminjamanPath = null;
            if ($request->hasFile('surat_peminjaman')) {
                $suratPeminjamanPath = $request->file('surat_peminjaman')->store('surat-peminjaman-kendaraan', 'public');
            }

            $dokumenPendukungPath = null;
            if ($request->hasFile('dokumen_pendukung')) {
                $dokumenPendukungPath = $request->file('dokumen_pendukung')->store('dokumen-pendukung', 'public');
            }

            // Prepare data for saving
            $saveData = [
                'token' => $token,
                'waktu_keberangkatan' => $request->input('waktu_keberangkatan'),
                'waktu_kepulangan' => $request->input('waktu_kepulangan'),
                'lokasi_keberangkatan' => $request->input('lokasi_keberangkatan'),
                'jumlah_rombongan' => $request->input('jumlah_rombongan'),
                'alamat_tujuan' => $request->input('alamat_tujuan'),
                'nama_kegiatan' => $request->input('nama_kegiatan'),
                'jenis_kegiatan' => $request->input('nama_kegiatan'), // Assuming jenis_kegiatan is same as nama_kegiatan for now
                'tujuan_wilayah_id' => $request->input('tujuan_wilayah_id'),
                'unit_kerja_id' => $request->input('unit_kerja_id'),
                'nama_pengguna' => $request->input('nama_pengguna'),
                'kontak_pengguna' => $request->input('kontak_pengguna'),
                'nama_personil_perwakilan' => $request->input('nama_personil_perwakilan'),
                'kontak_pengguna_perwakilan' => $request->input('kontak_pengguna_perwakilan'),
                'status_sebagai' => $request->input('status_sebagai'),
                'provinsi' => $request->input('provinsi'),
                'uraian_singkat_kegiatan' => $request->input('uraian_singkat_kegiatan'),
                'catatan_keterangan_tambahan' => $request->input('catatan_keterangan_tambahan'),
                'surat_peminjaman_kendaraan' => $suratPeminjamanPath, // Save file path
                'dokumen_pendukung' => $dokumenPendukungPath, // Save file path
                'status_perjalanan' => 'Menunggu Persetujuan',
                'jenis_operasional' => 'Peminjaman',
                'status_operasional' => 'Belum Ditetapkan',
                'pengemudi_id' => null,
                'nopol_kendaraan' => null,
            ];

            // Save to database
            $perjalanan = Perjalanan::create($saveData);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan peminjaman kendaraan berhasil diajukan!',
                'token' => $token,
                'tracking_url' => route('peminjaman.status', ['token' => $token]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show tracking status page
     */
    public function status($token)
    {
        $perjalanan = Perjalanan::where('token', $token)->firstOrFail();

        return view('peminjaman-status', [
            'perjalanan' => $perjalanan,
            'token' => $token,
        ]);
    }

    /**
     * Show success page after submission
     */
    public function success($token)
    {
        $perjalanan = Perjalanan::where('token', $token)->firstOrFail();

        return view('peminjaman-sukses', [
            'perjalanan' => $perjalanan,
            'token' => $token,
            'tracking_url' => route('peminjaman.status', ['token' => $token]),
        ]);
    }
}

