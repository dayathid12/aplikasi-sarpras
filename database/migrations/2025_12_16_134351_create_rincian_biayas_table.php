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
        Schema::create('rincian_biayas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rincian_pengeluaran_id')->constrained('rincian_pengeluarans')->cascadeOnDelete();
            $table->enum('tipe', ['bbm', 'toll', 'parkir']);
            $table->string('deskripsi')->nullable(); // Untuk Kode ATM, Kode Kartu Toll
            $table->decimal('volume', 8, 2)->nullable(); // Untuk Volume Liter BBM
            $table->string('jenis_bbm')->nullable(); // Untuk Jenis BBM
            $table->decimal('biaya', 15, 2);
            $table->string('bukti_path')->nullable(); // Untuk path semua file upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rincian_biayas');
    }
};