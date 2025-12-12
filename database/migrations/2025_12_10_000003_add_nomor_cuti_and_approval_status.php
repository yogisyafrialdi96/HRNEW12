<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration untuk menambah kolom nomor_cuti ke tabel cuti_pengajuan
     * 
     * Approval status sudah tersimpan di tabel cuti_approval dan cuti_approval_history
     * yang merekam setiap tahap approval. Tidak perlu kolom approval_status terpisah
     * karena bisa dikalkulasi dari relasi.
     */
    public function up(): void
    {
        Schema::table('cuti_pengajuan', function (Blueprint $table) {
            // Tambah kolom nomor_cuti dengan unique constraint
            // Format: CUTI/2024/0001 (auto-generated)
            $table->string('nomor_cuti')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('cuti_pengajuan', function (Blueprint $table) {
            $table->dropColumn('nomor_cuti');
        });
    }
};
