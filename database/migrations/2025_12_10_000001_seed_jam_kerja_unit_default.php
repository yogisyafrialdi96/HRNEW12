<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk setup jam_kerja_unit dengan default data
 * 
 * Pastikan sudah ada:
 * - master_unit table dengan data
 * - Sebelum run migration ini
 * 
 * Run: php artisan migrate
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed jam kerja unit untuk unit-unit yang ada
        // Ambil first 2 unit yang tersedia, atau gunakan unit_id 1 jika ada
        
        $units = \DB::table('master_unit')->where('is_active', true)->take(2)->get();
        
        if ($units->isEmpty()) {
            // Skip jika tidak ada unit
            return;
        }
        
        $jamKerjaUnitData = [];
        
        // Unit pertama: Standard Mon-Fri 8 jam
        $unit1 = $units->first();
        for ($hari = 1; $hari <= 7; $hari++) {
            $isLibur = in_array($hari, [6, 7]); // Sabtu & Minggu
            $jamKerjaUnitData[] = [
                'unit_id' => $unit1->id,
                'hari_ke' => $hari,
                'jam_masuk' => '08:00',
                'jam_pulang' => '17:00',
                'jam_istirahat' => $isLibur ? 0 : 60,
                'is_libur' => $isLibur,
                'is_full_day' => !$isLibur,
                'keterangan' => 'Hari ' . ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][$hari] . 
                               ($isLibur ? ' - Libur (tidak kerja)' : ' - Hari kerja normal'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Unit kedua: Shift pagi 6 jam (jika ada)
        if ($units->count() > 1) {
            $unit2 = $units->get(1);
            for ($hari = 1; $hari <= 7; $hari++) {
                $jamKerjaUnitData[] = [
                    'unit_id' => $unit2->id,
                    'hari_ke' => $hari,
                    'jam_masuk' => '07:00',
                    'jam_pulang' => '13:00',
                    'jam_istirahat' => 30,
                    'is_libur' => false,
                    'is_full_day' => false,
                    'keterangan' => 'Hari ' . ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][$hari] . 
                                   ' - Shift pagi 6 jam',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        if (!empty($jamKerjaUnitData)) {
            \DB::table('jam_kerja_unit')->insert($jamKerjaUnitData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete all jam_kerja_unit data (careful in production!)
        \DB::table('jam_kerja_unit')->truncate();
    }
};
