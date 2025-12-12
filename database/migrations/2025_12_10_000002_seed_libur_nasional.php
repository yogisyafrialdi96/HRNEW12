<?php

use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Get first user ID or use null for system-created data
        $userId = \DB::table('users')->first()?->id;
        
        $liburNasionalData = [
            // TAHUN 2025
            ['nama_libur' => 'Tahun Baru 2025', 'tanggal_libur' => '2025-01-01', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Perayaan Tahun Baru', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Isra dan Mi\'raj', 'tanggal_libur' => '2025-02-27', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Isra dan Mi\'raj', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Nyepi (Bali)', 'tanggal_libur' => '2025-03-29', 'tanggal_libur_akhir' => null, 'tipe' => 'lokal', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Nyepi (Bali)', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Hari Raya Paskah', 'tanggal_libur' => '2025-04-20', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Paskah', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Lebaran', 'tanggal_libur' => '2025-04-10', 'tanggal_libur_akhir' => '2025-04-14', 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Lebaran (Idul Fitri)', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Hari Raya Idul Adha', 'tanggal_libur' => '2025-06-16', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Idul Adha', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Tahun Baru Hijriah', 'tanggal_libur' => '2025-07-07', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Tahun Baru Hijriah', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Mawlid Nabi Muhammad', 'tanggal_libur' => '2025-09-16', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Mawlid Nabi Muhammad SAW', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Hari Kemerdekaan Indonesia', 'tanggal_libur' => '2025-08-17', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Kemerdekaan Indonesia', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Hari Raya Natal', 'tanggal_libur' => '2025-12-25', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Natal Yesus Kristus', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Cuti Bersama', 'tanggal_libur' => '2025-12-26', 'tanggal_libur_akhir' => '2025-12-31', 'tipe' => 'cuti_bersama', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Cuti Bersama menjelang Tahun Baru 2026', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            
            // TAHUN 2026
            ['nama_libur' => 'Tahun Baru 2026', 'tanggal_libur' => '2026-01-01', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Perayaan Tahun Baru', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Isra dan Mi\'raj 2026', 'tanggal_libur' => '2026-02-17', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Isra dan Mi\'raj', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Lebaran 2026', 'tanggal_libur' => '2026-03-30', 'tanggal_libur_akhir' => '2026-04-03', 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Lebaran (Idul Fitri)', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Hari Raya Paskah 2026', 'tanggal_libur' => '2026-04-05', 'tanggal_libur_akhir' => null, 'tipe' => 'nasional', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Paskah', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
            ['nama_libur' => 'Nyepi 2026 (Bali)', 'tanggal_libur' => '2026-03-19', 'tanggal_libur_akhir' => null, 'tipe' => 'lokal', 'provinsi_id' => null, 'is_active' => true, 'keterangan' => 'Hari Raya Nyepi (Bali)', 'created_by' => $userId, 'created_at' => now(), 'updated_at' => now()],
        ];
        
        if (!empty($liburNasionalData)) {
            \DB::table('libur_nasional')->insert($liburNasionalData);
        }
    }

    public function down(): void
    {
        \DB::table('libur_nasional')->truncate();
    }
};
