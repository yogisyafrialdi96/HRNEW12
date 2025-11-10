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
        Schema::table('karyawan_kontrak', function (Blueprint $table) {
            // First add the new column
            $table->unsignedBigInteger('mapel_id')->nullable()->after('jabatan_id');
            
            // Add foreign key constraint
            $table->foreign('mapel_id')
                  ->references('id')
                  ->on('master_mapel')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            // Remove old column if it exists
            if (Schema::hasColumn('karyawan_kontrak', 'mapel')) {
                $table->dropColumn('mapel');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan_kontrak', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['mapel_id']);
            
            // Drop the new column
            $table->dropColumn('mapel_id');
            
            // Add back the old column
            $table->string('mapel')->nullable()->after('jabatan_id');
        });
    }
};