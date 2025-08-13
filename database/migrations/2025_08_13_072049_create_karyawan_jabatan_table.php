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
        Schema::create('karyawan_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('master_department')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('master_unit')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('master_jabatan')->onDelete('restrict');
            $table->foreignId('mapel_id')->constrained('master_mapel')->onDelete('restrict');
            $table->enum('hub_kerja', ['Mutasi', 'Promosi','Demosi','Rotasi','Default','Penugasan Sementara'])->default('Default');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai')->nullable();
            $table->string('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Indexes
            $table->index(['karyawan_id', 'is_active', 'deleted_at']);
            $table->index(['department_id', 'deleted_at']);
            $table->index(['unit_id', 'deleted_at']);
            $table->index(['jabatan_id', 'deleted_at']);
            $table->index(['tgl_mulai', 'tgl_selesai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_jabatan');
    }
};
