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
        Schema::create('karyawan_pelatihan', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel karyawan
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            
            // Informasi pelatihan
            $table->string('nama_pelatihan');
            $table->string('penyelenggara');
            $table->string('lokasi');
            
            // Tanggal pelatihan
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            
            // Jenis pelatihan dengan enum yang lebih spesifik
            $table->enum('jenis_pelatihan', [
                'internal', 
                'eksternal', 
                'online', 
                'offline', 
                'hybrid'
            ])->default('eksternal');
            
            // Sertifikat
            $table->boolean('sertifikat_diperoleh')->default(false);
            $table->string('document_path')->nullable();
            
            // Keterangan tambahan
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->comment('User yang menginput');
                  
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('User yang mengupdate');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['karyawan_id', 'tgl_mulai']);
            $table->index('jenis_pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_pelatihan');
    }
};
