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
        Schema::create('karyawan_sertifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->string('nama_sertifikasi');
            $table->string('lembaga_penerbit');
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tgl_terbit');
            $table->date('tgl_kadaluwarsa')->nullable();
            $table->integer('masa_berlaku_tahun')->nullable(); // Masa berlaku standar dalam tahun
            $table->decimal('biaya_sertifikasi', 12, 2)->nullable(); // Biaya yang dikeluarkan
            $table->enum('metode_pembelajaran',['Online', 'Offline', 'Blended'])->nullable(); // Online, Offline, Blended
            $table->integer('durasi_jam')->nullable(); // Durasi pelatihan dalam jam
            $table->boolean('wajib_perpanjang')->default(false); // Apakah wajib diperpanjang
            $table->enum('jenis_sertifikasi', [
                'profesi',
                'keahlian',
                'pelatihan',
                'kompetensi',
                'bahasa',
                'teknologi',
                'manajemen',
                'keselamatan',
                'lainnya'
            ])->default('profesi');
            $table->enum('tingkat', [
                'dasar',
                'menengah',
                'lanjut',
                'ahli',
                'master'
            ])->nullable();
            $table->string('document_path')->nullable();
            $table->enum('status_sertifikat', [
                'aktif',
                'kadaluwarsa',
                'dicabut',
                'dalam_proses'
            ])->default('aktif');
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

            // Foreign key constraint
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            
            // Index untuk performa query
            $table->index('karyawan_id');
            $table->index('jenis_sertifikasi');
            $table->index('status_sertifikat');
            $table->index('tgl_kadaluwarsa');
            $table->index(['karyawan_id', 'jenis_sertifikasi']);
            
            // Unique constraint untuk nomor sertifikat jika ada
            $table->unique(['nomor_sertifikat', 'lembaga_penerbit'], 'unique_nomor_lembaga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_sertifikasi');
    }
};
