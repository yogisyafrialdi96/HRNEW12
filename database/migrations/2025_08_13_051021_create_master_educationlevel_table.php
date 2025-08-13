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
        Schema::create('master_educationlevel', function (Blueprint $table) {
            $table->id();
            $table->string('level_code', 10)->unique()->comment('Kode level pendidikan');
            $table->string('level_name', 50)->comment('Nama level pendidikan');
            $table->integer('level_order')->comment('Urutan level untuk sorting');
            $table->boolean('is_formal')->default(true)->comment('Pendidikan formal/non-formal');
            $table->integer('minimum_years')->nullable()->comment('Durasi minimum dalam tahun');
            $table->text('description')->nullable()->comment('Deskripsi level pendidikan');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'level_order']);
            $table->index('is_formal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_educationlevel');
    }
};
