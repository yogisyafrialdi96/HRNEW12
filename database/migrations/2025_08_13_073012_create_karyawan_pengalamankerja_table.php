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
        Schema::create('karyawan_pengalamankerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')
                  ->constrained('karyawan')
                  ->onDelete('cascade')
                  ->comment('Referensi ke tabel karyawan');
                  
            $table->string('nama_instansi', 200)
                  ->comment('Nama perusahaan/instansi');

            $table->string('departemen', 100)
                    ->nullable()
                    ->comment('Departemen/divisi');
                  
            $table->string('jabatan', 150)
                  ->comment('Jabatan/posisi yang dijabat');
                  
            $table->string('lokasi_pekerjaan', 200)
                  ->nullable()
                  ->comment('Lokasi/alamat tempat kerja');
                  
            $table->string('bidang_industri', 100)
                  ->nullable()
                  ->comment('Bidang industri perusahaan');
                  
            $table->enum('jenis_kontrak', [
                'kontrak',
                'tetap', 
                'magang',
                'freelance',
                'konsultan',
                'paruh_waktu',
                'harian',
                'borongan',
                'lainnya'
            ])->comment('Jenis kontrak pekerjaan');
            
            $table->date('tgl_awal')
                  ->comment('Tanggal mulai bekerja');
                  
            $table->date('tgl_akhir')
                  ->nullable()
                  ->comment('Tanggal selesai kerja (null jika masih bekerja)');
                  
            $table->enum('status_kerja', [
                'aktif',
                'selesai',
                'resign',
                'phk',
                'mutasi',
                'pensiun'
            ])->default('selesai')->comment('Status pekerjaan');

            // Additional recommended fields
            $table->decimal('gaji_awal', 15, 2)
                  ->nullable()
                  ->comment('Gaji awal saat mulai bekerja');
                  
            $table->decimal('gaji_akhir', 15, 2)
                  ->nullable()
                  ->comment('Gaji terakhir sebelum keluar');
                  
            $table->string('mata_uang', 5)
                  ->default('IDR')
                  ->comment('Mata uang gaji');
            
            $table->text('peran')
                  ->nullable()
                  ->comment('Deskripsi tugas dan tanggung jawab');
                  
            $table->text('alasan_berhenti')
                  ->nullable()
                  ->comment('Alasan berhenti/pindah kerja');
                  
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
            
            // Indexes for better performance
            $table->index(['karyawan_id', 'status_kerja']);
            $table->index(['tgl_awal', 'tgl_akhir']);
            $table->index(['status_kerja']);
            $table->index('jenis_kontrak');
            $table->index(['nama_instansi', 'jabatan']);
            
            // Composite indexes for common queries
            $table->index(['karyawan_id', 'tgl_awal', 'tgl_akhir'], 'idx_karyawan_periode_kerja');
            $table->index(['karyawan_id', 'status_kerja', 'tgl_akhir'], 'idx_karyawan_status_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_pengalamankerja');
    }
};
