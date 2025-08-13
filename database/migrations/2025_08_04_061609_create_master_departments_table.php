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
        Schema::create('master_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('master_companies');
            $table->string('department');
            $table->string('kode_department', 10)->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('kepala_department')->nullable()->constrained('users');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            // Indexes
            $table->index(['company_id', 'status', 'deleted_at']);
            $table->index('kepala_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_departments');
    }
};
