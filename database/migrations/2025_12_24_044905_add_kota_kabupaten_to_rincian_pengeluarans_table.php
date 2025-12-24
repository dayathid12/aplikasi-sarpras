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
        Schema::table('rincian_pengeluarans', function (Blueprint $table) {
            $table->string('kota_kabupaten')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rincian_pengeluarans', function (Blueprint $table) {
            $table->dropColumn('kota_kabupaten');
        });
    }
};
