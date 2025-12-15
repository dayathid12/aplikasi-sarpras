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
            // Drop the existing foreign key constraint that references 'nomor_perjalanan'
            // The name of the foreign key constraint can vary, so we need to find it or drop it by columns.
            // A common naming convention is table_column_foreign
            $table->dropForeign(['perjalanan_id']);

            // Add the new foreign key constraint that references 'id'
            $table->foreign('perjalanan_id')->references('id')->on('perjalanans')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            // Revert by dropping the foreign key to 'id'
            $table->dropForeign(['perjalanan_id']);

            // Re-add the original foreign key to 'nomor_perjalanan' for rollback purposes
            // This assumes 'nomor_perjalanan' still exists and is unique/indexed.
            $table->foreign('perjalanan_id')->references('nomor_perjalanan')->on('perjalanans')->cascadeOnDelete();
        });
    }
};