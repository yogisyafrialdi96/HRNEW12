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
        Schema::create('izin_alasan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alasan'); // e.g., "Sakit", "Keperluan Keluarga", "Ibadah Keagamaan"
            $table->string('jenis_izin'); // 'jam' atau 'hari'
            $table->integer('max_hari_setahun')->nullable(); // null = unlimited
            $table->boolean('is_bayar_penuh')->default(false); // Apakah digaji penuh saat diambil
            $table->boolean('perlu_surat_dokter')->default(false); // Apakah butuh dokumen dukung
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // Untuk sorting di form
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_alasan');
    }
};
