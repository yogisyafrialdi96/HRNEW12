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
        Schema::create('master_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('master_departments');
            $table->string('nama_jabatan');
            $table->string('kode_jabatan', 10)->nullable();
            $table->enum('jenis_jabatan', ['struktural', 'fungsional','pelaksana']);
            $table->enum('level_jabatan',['top_managerial','middle_manager','supervisor','staff','staff_operasional','operator','phl','jabatan_khusus'])->nullable();
            $table->text('tugas_pokok')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('min_salary', 15, 2)->nullable();
            $table->decimal('max_salary', 15, 2)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            // Indexes
            $table->index(['department_id', 'status', 'deleted_at']);
            $table->index(['level_jabatan','jenis_jabatan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jabatans');
    }
};
