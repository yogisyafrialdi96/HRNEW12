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
        Schema::create('master_unit', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->foreignId('department_id')->constrained('master_department');
            $table->string('kode_unit', 10)->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('kepala_unit')->nullable()->constrained('users');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            // Indexes
            $table->index(['department_id', 'status', 'deleted_at']);
            $table->index('kepala_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_unit');
    }
};
