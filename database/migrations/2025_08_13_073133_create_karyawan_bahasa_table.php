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
        Schema::create('karyawan_bahasa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')
                  ->constrained('karyawan')
                  ->onDelete('cascade')
                  ->comment('Referensi ke tabel karyawan');
            $table->string('nama_bahasa');
            $table->enum('level_bahasa', [
                'pemula', 
                'dasar', 
                'menengah', 
                'mahir', 
                'fasih',
                'native'
            ])->nullable();
            $table->enum('jenis_test', ['IELTS', 'TOEFL','CAE','TOEIC','HSK', 'JLPT', 'DELF', 'TestDaF','Lainnya']);
            $table->string('jenistest_lain')->nullable();
            $table->string('lembaga_sertifikasi')->nullable(); // TOEFL, IELTS, HSK, dll
            $table->integer('skor_numerik')->nullable(); // 450, 550, 6.0, dll
            $table->boolean('is_active')->default(true);
            $table->date('tgl_expired_sertifikasi')->nullable();
            $table->date('tgl_sertifikasi')->nullable();
            $table->text('keterangan')->nullable();      
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->comment('User yang menginput');
                  
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('User yang mengupdate');
            $table->timestamps();

            // Index untuk performa query
            $table->index('karyawan_id');
            $table->index('nama_bahasa');
            $table->index('level_bahasa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_bahasa');
    }
};
