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
        Schema::create('karyawan_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('education_level_id')->constrained('master_educationlevel')->onDelete('cascade');
            $table->string('nama_institusi', 200);
            $table->enum('jenis_institusi', ['negeri', 'swasta', 'internasional']);
            $table->string('fakultas', 150)->nullable();
            $table->string('jurusan', 150)->nullable();
            $table->string('spesialisasi', 100)->nullable();
            $table->string('gelar', 100)->nullable();
            $table->year('tahun_mulai');
            $table->year('tahun_selesai')->nullable();
            $table->date('tanggal_ijazah')->nullable();
            $table->string('nomor_ijazah', 50)->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->decimal('skala_ipk', 3, 2)->default(4.00)->nullable();
            $table->text('judul_skripsi')->nullable();
            $table->string('negara', 50)->default('Indonesia')->nullable();
            $table->string('kota', 100)->nullable();
            $table->enum('akreditasi', ['A', 'B', 'C', 'unaccredited', 'international'])->nullable();
            $table->enum('jenis_belajar', ['full_time', 'part_time', 'distance', 'online'])->default('full_time');
            $table->enum('sumber_dana', ['pribadi', 'beasiswa', 'perusahaan', 'pemerintah'])->nullable();
            $table->string('nama_beasiswa', 100)->nullable();
            $table->boolean('is_current')->default(false);
            $table->enum('status', ['completed', 'ongoing', 'dropped_out', 'transferred'])->default('completed');
            $table->string('document_path', 255)->nullable();
            $table->text('ket')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['karyawan_id', 'education_level_id']);
            $table->index(['status', 'is_current']);
            $table->index(['negara', 'kota']);
            $table->index(['tahun_mulai', 'tahun_selesai']);
            $table->index('tanggal_ijazah');
            $table->index('jenis_institusi');
            $table->index('sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_pendidikan');
    }
};
