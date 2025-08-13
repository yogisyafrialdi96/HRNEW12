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
        Schema::create('karyawan_organisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')
                  ->constrained('karyawan')
                  ->onDelete('cascade')
                  ->comment('Referensi ke tabel employees');
                  
            $table->string('organisasi', 200)
                  ->comment('Nama organisasi');

            $table->enum('level', ['sekolah','fakultas','universitas','nasional', 'regional', 'lokal', 'internasional'])
                  ->comment('Level organisasi (nasional, regional, lokal, internasional)');
                  
            $table->string('jabatan', 100)
                  ->comment('Jabatan dalam organisasi');
                  
            $table->date('tgl_awal')
                  ->comment('Tanggal mulai bergabung');
                  
            $table->date('tgl_akhir')
                  ->nullable()
                  ->comment('Tanggal selesai (null jika masih aktif)');
                  
            $table->enum('status_organisasi', ['aktif', 'tidak_aktif', 'alumni', 'pensiun'])
                  ->default('aktif')
                  ->comment('Status keanggotaan dalam organisasi');
                  
            $table->text('peran')
                  ->nullable()
                  ->comment('Deskripsi peran dan tanggung jawab');
                  
            // Additional recommended fields
            $table->enum('jenis_organisasi', [
                'profesi', 
                'kemasyarakatan', 
                'keagamaan', 
                'politik', 
                'pendidikan',
                'sosial',
                'ekonomi',
                'budaya',
                'olahraga',
                'lainnya'
            ])->nullable()->comment('Kategori jenis organisasi');

            $table->string('jenisorg_lain', 150)->nullable()
                  ->comment('Jenis organisasi lainnya');
            
            $table->string('alamat_organisasi', 255)
                  ->nullable()
                  ->comment('Alamat kantor organisasi');
                  
            $table->string('website', 100)
                  ->nullable()
                  ->comment('Website organisasi');
                  
            $table->string('email_organisasi', 100)
                  ->nullable()
                  ->comment('Email organisasi');
                  
            $table->string('document_path', 255)
                  ->nullable()
                  ->comment('Path file sertifikat/surat keanggotaan');
                  
            $table->text('catatan')
                  ->nullable()
                  ->comment('Catatan tambahan');
                  
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
            $table->index(['karyawan_id', 'status_organisasi']);
            $table->index(['tgl_awal', 'tgl_akhir']);
            $table->index(['status_organisasi']);
            $table->index('jenis_organisasi');
            $table->index('level');
            $table->index(['organisasi', 'jabatan']);
            
            // Composite index for common queries
            $table->index(['karyawan_id', 'tgl_awal', 'tgl_akhir'], 'idx_karyawan_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_organisasi');
    }
};
