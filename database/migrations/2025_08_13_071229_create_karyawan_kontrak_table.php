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
        Schema::create('karyawan_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kontrak', 50)->unique();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('kontrak_id')->constrained('master_kontrak')->onDelete('cascade');
            $table->foreignId('golongan_id')->constrained('master_golongan')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('master_unit')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('master_jabatan')->onDelete('cascade');
            $table->string('mapel')->nullable();
            $table->string('gaji_paket')->nullable();
            $table->string('gaji_pokok')->nullable();
            $table->string('transport')->nullable();
            $table->date('tglmulai_kontrak');
            $table->date('tglselesai_kontrak')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'perpanjangan','dibatalkan'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('approved_1')->nullable()->constrained('karyawan');
            $table->boolean('status_approve_1')->nullable();
            $table->foreignId('approved_2')->nullable()->constrained('pengurus');
            $table->boolean('status_approve_2')->nullable();
            $table->softDeletes(); // Untuk soft delete
            $table->timestamps();

            // Indexes
            $table->index(['nomor_kontrak', 'deleted_at']);
            $table->index(['tglmulai_kontrak', 'deleted_at']);
            $table->index(['status', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_kontrak');
    }
};
