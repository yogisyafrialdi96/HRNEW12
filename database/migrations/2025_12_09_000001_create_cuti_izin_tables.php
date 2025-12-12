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
        // Table: cuti_setup
        Schema::create('cuti_setup', function (Blueprint $table) {
            $table->id();
            
            // Cuti Tahunan
            $table->integer('h_min_cuti_tahunan')->default(7);
            $table->integer('max_cuti_tahunan_per_tahun')->default(12);
            $table->integer('max_carry_over')->default(5);
            
            // Cuti Melahirkan
            $table->integer('hari_cuti_melahirkan')->default(45);
            $table->integer('h_min_cuti_melahirkan')->default(14);
            
            // Hari Kerja
            $table->string('hari_kerja')->default('1,2,3,4,5');
            $table->integer('jam_kerja_per_hari')->default(8);
            
            // Tracking
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: izin_setup
        Schema::create('izin_setup', function (Blueprint $table) {
            $table->id();
            
            // Izin Sakit
            $table->integer('h_min_izin_sakit')->default(1);
            $table->integer('max_izin_sakit_per_tahun')->default(10);
            $table->boolean('sakit_perlu_surat_dokter')->default(false);
            $table->integer('hari_ke_berapa_perlu_dokter')->default(3);
            
            // Izin Penting
            $table->integer('h_min_izin_penting')->default(3);
            $table->integer('max_izin_penting_per_tahun')->default(3);
            
            // Izin Ibadah
            $table->integer('h_min_izin_ibadah')->default(7);
            $table->integer('max_hari_ibadah_per_tahun')->default(3);
            
            // Aturan Umum
            $table->boolean('tidak_hitung_libnas')->default(true);
            $table->boolean('tidak_hitung_libur_unit')->default(true);
            
            // Tracking
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: libur_nasional
        Schema::create('libur_nasional', function (Blueprint $table) {
            $table->id();
            
            $table->string('nama_libur');
            $table->date('tanggal_libur');
            $table->date('tanggal_libur_akhir')->nullable();
            
            $table->enum('tipe', ['nasional', 'lokal', 'cuti_bersama'])->default('nasional');
            $table->unsignedBigInteger('provinsi_id')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: jam_kerja_unit
        Schema::create('jam_kerja_unit', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('unit_id');
            $table->integer('hari_ke');
            
            $table->time('jam_masuk')->default('07:15');
            $table->time('jam_pulang')->default('16:00');
            $table->integer('jam_istirahat')->default(60);
            
            $table->boolean('is_libur')->default(false);
            $table->boolean('is_full_day')->default(true);
            
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('unit_id')->references('id')->on('master_unit')->onDelete('cascade');
            $table->unique(['unit_id', 'hari_ke']);
        });

        // Table: cuti_saldo
        Schema::create('cuti_saldo', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            
            // Cuti Tahunan
            $table->integer('cuti_tahunan_awal')->default(12);
            $table->integer('cuti_tahunan_terpakai')->default(0);
            $table->integer('cuti_tahunan_sisa');
            
            // Cuti Melahirkan
            $table->integer('cuti_melahirkan_awal')->default(0);
            $table->integer('cuti_melahirkan_terpakai')->default(0);
            $table->integer('cuti_melahirkan_sisa');
            
            // Carry Over
            $table->integer('carry_over_tahunan')->default(0);
            $table->integer('carry_over_digunakan')->default(0);
            
            // Tracking
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('master_tahunajaran')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->unique(['user_id', 'tahun_ajaran_id']);
        });

        // Table: cuti_pengajuan
        Schema::create('cuti_pengajuan', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cuti_saldo_id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            
            $table->enum('jenis_cuti', ['tahunan', 'melahirkan']);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled'])->default('draft');
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            
            $table->text('alasan')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('phone')->nullable();
            
            // Cuti Melahirkan
            $table->date('tanggal_estimasi_lahir')->nullable();
            $table->date('tanggal_surat_dokter')->nullable();
            $table->string('nama_dokter')->nullable();
            
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('catatan_reject')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cuti_saldo_id')->references('id')->on('cuti_saldo')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('master_tahunajaran')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: cuti_approval
        Schema::create('cuti_approval', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('cuti_pengajuan_id');
            $table->unsignedBigInteger('atasan_user_id');
            
            $table->integer('level');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('komentar')->nullable();
            
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('urutan_approval')->default(1);
            $table->timestamps();
            
            $table->foreign('cuti_pengajuan_id')->references('id')->on('cuti_pengajuan')->onDelete('cascade');
            $table->foreign('atasan_user_id')->references('id')->on('atasan_user')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: cuti_approval_history
        Schema::create('cuti_approval_history', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('cuti_pengajuan_id');
            $table->enum('action', ['created', 'submitted', 'approved', 'rejected', 'cancelled']);
            $table->unsignedBigInteger('user_id');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('cuti_pengajuan_id')->references('id')->on('cuti_pengajuan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });

        // Table: izin_pengajuan
        Schema::create('izin_pengajuan', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->unsignedBigInteger('izin_alasan_id')->nullable();
            
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled'])->default('draft');
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->integer('jumlah_jam')->nullable();
            
            $table->text('alasan');
            
            $table->string('file_surat_dokter')->nullable();
            $table->date('tanggal_surat_dokter')->nullable();
            
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('catatan_reject')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('master_tahunajaran')->onDelete('cascade');
            $table->foreign('izin_alasan_id')->references('id')->on('izin_alasan')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: izin_approval
        Schema::create('izin_approval', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('izin_pengajuan_id');
            $table->unsignedBigInteger('atasan_user_id');
            
            $table->integer('level');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('komentar')->nullable();
            
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('urutan_approval')->default(1);
            $table->timestamps();
            
            $table->foreign('izin_pengajuan_id')->references('id')->on('izin_pengajuan')->onDelete('cascade');
            $table->foreign('atasan_user_id')->references('id')->on('atasan_user')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Table: izin_approval_history
        Schema::create('izin_approval_history', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('izin_pengajuan_id');
            $table->enum('action', ['created', 'submitted', 'approved', 'rejected', 'cancelled']);
            $table->unsignedBigInteger('user_id');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('izin_pengajuan_id')->references('id')->on('izin_pengajuan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_approval_history');
        Schema::dropIfExists('izin_approval');
        Schema::dropIfExists('izin_pengajuan');
        Schema::dropIfExists('cuti_approval_history');
        Schema::dropIfExists('cuti_approval');
        Schema::dropIfExists('cuti_pengajuan');
        Schema::dropIfExists('cuti_saldo');
        Schema::dropIfExists('jam_kerja_unit');
        Schema::dropIfExists('libur_nasional');
        Schema::dropIfExists('izin_setup');
        Schema::dropIfExists('cuti_setup');
    }
};
