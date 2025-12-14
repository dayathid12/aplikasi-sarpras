<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rincian_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_pengeluaran_id')->constrained('entry_pengeluarans')->cascadeOnDelete();
            $table->unsignedBigInteger('perjalanan_id')->nullable(); // Referensi ke perjalanan asli (opsional)

            $table->string('nomor_perjalanan')->nullable();
            $table->string('nama_pengemudi')->nullable();
            $table->dateTime('waktu_keberangkatan')->nullable();
            $table->string('alamat_tujuan')->nullable();
            $table->string('nama_unit_kerja')->nullable();
            $table->string('nopol_kendaraan')->nullable();

            $table->decimal('biaya_parkir', 10, 2)->nullable();
            $table->string('upload_bukti_parkir')->nullable();
            $table->decimal('total', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rincian_pengeluarans');
    }
};
