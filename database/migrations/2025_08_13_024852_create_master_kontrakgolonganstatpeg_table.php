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
        Schema::create('master_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kontrak')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        Schema::create('master_golongan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_golongan')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        Schema::create('master_statuspegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_status');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_statuspegawai');
        Schema::dropIfExists('master_golongan');
        Schema::dropIfExists('master_kontrak');
    }
};
