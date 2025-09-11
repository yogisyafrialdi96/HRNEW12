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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip', 20)->unique();
            $table->string('inisial',3)->unique();
            $table->string('full_name');
            $table->string('panggilan')->nullable();
            $table->string('hp',15)->nullable();
            $table->string('whatsapp',15)->nullable();
            $table->enum('gender',['laki-laki','perempuan']);
            $table->string('gelar_depan',10)->nullable();
            $table->string('gelar_belakang',10)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('pndk_akhir',['SD','SMP','SMA','D1','D2','D3','D4','S1','S2','S3']);
            $table->enum('agama',['Islam','Hindu','Budha','Katolik','Protestan','Konghucu']);
            $table->enum('status_kawin', ['lajang', 'menikah', 'cerai'])->default('lajang');
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 15)->nullable();

            // Alamat KTP
            $table->string('alamat_ktp')->nullable();
            $table->string('rt_ktp')->nullable();
            $table->string('rw_ktp')->nullable();
            $table->foreignId('prov_id')->constrained('provinsi');
            $table->foreignId('kab_id')->constrained('kabupaten');
            $table->foreignId('kec_id')->constrained('kecamatan');
            $table->foreignId('desa_id')->constrained('desa');

            // Alamat Domisili (boleh kosong jika sama dengan KTP)
            $table->boolean('domisili_sama_ktp')->default(false);
            $table->string('alamat_dom')->nullable();
            $table->string('rt_dom')->nullable();
            $table->string('rw_dom')->nullable();
            $table->foreignId('provdom_id')->nullable()->constrained('provinsi');
            $table->foreignId('kabdom_id')->nullable()->constrained('kabupaten');
            $table->foreignId('kecdom_id')->nullable()->constrained('kecamatan');
            $table->foreignId('desdom_id')->nullable()->constrained('desa');

            $table->string('nik')->nullable();
            $table->string('nkk')->nullable();
            $table->string('foto')->nullable();
            $table->string('ttd')->nullable();
            $table->foreignId('statuskaryawan_id')->constrained('master_statuspegawai');
            $table->foreignId('statuskawin_id')->constrained('master_statuskawin');
            $table->foreignId('golongan_id')->constrained('master_golongan');
            
            $table->string('npwp')->nullable();
            
            $table->date('tgl_masuk');
            $table->date('tgl_karyawan_tetap')->nullable();
            $table->date('tgl_berhenti')->nullable();

            $table->enum('jenis_karyawan',['Guru','Pegawai'])->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');


            // Indexes
            $table->index(['user_id', 'deleted_at']);
            $table->index(['nip', 'deleted_at']);
            $table->index(['statuskaryawan_id', 'deleted_at']);
            $table->index('tgl_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
