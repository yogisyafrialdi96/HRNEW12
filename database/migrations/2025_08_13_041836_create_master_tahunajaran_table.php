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
        Schema::create('master_tahunajaran', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->date('awal_periode');
            $table->date('akhir_periode');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Indexes
            $table->index(['is_active', 'deleted_at']);
            $table->index(['awal_periode', 'akhir_periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tahunajaran');
    }
};
