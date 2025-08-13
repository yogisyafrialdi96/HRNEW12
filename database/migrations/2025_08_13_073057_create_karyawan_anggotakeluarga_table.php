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
        Schema::create('karyawan_anggotakeluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')
                  ->constrained('karyawan')
                  ->onDelete('cascade')
                  ->comment('Referensi ke tabel karyawan');
            $table->string('nama_anggota');
            $table->enum('hubungan', [
                'suami', 
                'istri', 
                'anak', 
                'ayah', 
                'ibu', 
                'saudara', 
                'mertua',
                'lainnya'
            ]);
            $table->string('hubungan_lain')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('pekerjaan')->nullable();
            $table->enum('status_hidup', ['Hidup', 'Meninggal'])->default('Hidup');
            $table->boolean('ditanggung')->default(false);
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
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
            $table->index('karyawan_id');
            $table->index('hubungan');
            $table->index('status_hidup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_anggotakeluarga');
    }
};
