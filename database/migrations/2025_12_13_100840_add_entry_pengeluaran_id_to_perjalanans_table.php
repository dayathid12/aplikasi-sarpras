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
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->unsignedBigInteger('entry_pengeluaran_id')->nullable()->after('nomor_perjalanan');
            $table->foreign('entry_pengeluaran_id')->references('id')->on('entry_pengeluarans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->dropForeign(['entry_pengeluaran_id']);
            $table->dropColumn('entry_pengeluaran_id');
        });
    }
};
