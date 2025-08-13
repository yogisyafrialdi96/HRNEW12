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
        Schema::create('karyawan_dokumen', function (Blueprint $table) {
            $table->id();
            // Foreign key constraint
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->enum('jenis_dokumen', ['foto','ktp','kk','ijazah','transkrip','skck','sertifikat','npwp','buku_tabungan','lainnya']);
            $table->string('jenis_lainnya')->nullable(); // jika pilih jenis lainnya
            $table->enum('status_dokumen', ['valid', 'invalid', 'waiting'])->default('waiting');
            $table->string('document_path')->nullable(); // file path
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_dokumen');
    }
};
