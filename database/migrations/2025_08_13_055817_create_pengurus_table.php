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
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('master_jabatan')->onDelete('cascade');
            $table->string('nama_pengurus');
            $table->string('inisial',3)->unique();
            $table->string('hp',15);
            $table->enum('jenis_kelamin',['laki-laki','perempuan']);
            $table->string('gelar_depan',10)->nullable();
            $table->string('gelar_belakang',10)->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('foto')->nullable();
            $table->string('ttd')->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('posisi',['ketua','anggota',])->default('anggota');
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['nama_pengurus', 'is_active','posisi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengurus');
    }
};
