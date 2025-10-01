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
        Schema::create('karyawan_prestasi', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel karyawan
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            
            // Informasi prestasi
            $table->string('nama_prestasi');
            $table->enum('tingkat', ['lokal', 'regional', 'nasional', 'internasional']);
            $table->enum('peringkat', ['juara_1', 'juara_2', 'juara_3', 'harapan_1', 'harapan_2', 'harapan_3', 'partisipasi', 'nominasi']);
            $table->enum('kategori', ['individu', 'tim', 'organisasi']);
            $table->string('penyelenggara');
            $table->date('tanggal');
            $table->string('lokasi');
            
            // Keterangan tambahan
            $table->text('keterangan')->nullable();
            $table->string('document_path')->nullable(); //
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
            
            // Index untuk optimasi query
            $table->index(['karyawan_id', 'tanggal']);
            $table->index('tingkat');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_prestasi');
    }
};
