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
        Schema::table('jadwal_pengemudis', function (Blueprint $table) {
            $table->foreignId('perjalanan_id')->nullable()->constrained('perjalanans', 'id')->onDelete('set null');
            $table->string('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengemudis', function (Blueprint $table) {
            $table->dropForeign(['perjalanan_id']);
            $table->dropColumn('perjalanan_id');
            $table->dropColumn('keterangan');
        });
    }
};
