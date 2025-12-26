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
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            $table->timestamp('waktu_selesai_penugasan')->nullable()->after('tipe_penugasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            $table->dropColumn('waktu_selesai_penugasan');
        });
    }
};
