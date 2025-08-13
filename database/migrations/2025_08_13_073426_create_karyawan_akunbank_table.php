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
        Schema::create('karyawan_akunbank', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->string('nama_bank');
            $table->string('kode_bank', 10);
            $table->string('nomor_rekening', 50);
            $table->string('nama_pemilik'); // Nama sesuai rekening
            $table->enum('jenis_rekening', ['tabungan', 'giro', 'deposito', 'kredit']);
            $table->enum('tujuan', ['gaji', 'bonus', 'tunjangan', 'reimburse', 'pribadi']);
            $table->string('cabang')->nullable();
            $table->boolean('is_primary')->default(false); // Rekening utama untuk gaji
            $table->enum('status', ['aktif', 'nonaktif', 'blocked', 'closed'])->default('aktif');
            $table->date('tanggal_buka')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_buku_tabungan')->nullable(); // Path file foto buku tabungan
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

            // indexing
            $table->index(['karyawan_id', 'is_primary']);
            $table->index(['nama_bank', 'status']);
            $table->unique(['karyawan_id', 'nomor_rekening', 'nama_bank'], 'unique_karyawan_rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_akunbank');
    }
};
