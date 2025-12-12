<?php

namespace App\Services;

use App\Models\IzinCuti\CutiPengajuan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk generate nomor cuti dengan format:
 * NO-AUTO/YKPI-CUTI/BULAN-ROMAWI/TAHUN
 * 
 * Contoh: 001/YKPI-CUTI/XII/2025
 */
class CutiNumberGenerator
{
    /**
     * Generate nomor cuti dengan format AUTO
     */
    public static function generate(): string
    {
        return DB::transaction(function () {
            $now = Carbon::now();
            $bulanRomawi = self::getBulanRomawi($now->month);
            $tahun = $now->year;
            
            // Hitung sequence untuk bulan dan tahun ini dengan lock
            $sequence = self::getNextSequence($now->month, $tahun);
            
            return sprintf(
                '%03d/YKPI-CUTI/%s/%d',
                $sequence,
                $bulanRomawi,
                $tahun
            );
        });
    }
    
    /**
     * Get next sequence number untuk bulan dan tahun tertentu dengan locking
     * Include soft-deleted records karena nomor cuti harus unique bahkan untuk yang dihapus
     */
    private static function getNextSequence(int $bulan, int $tahun): int
    {
        // Use database lock untuk prevent race condition
        // Include soft-deleted records in count
        $count = CutiPengajuan::withTrashed()
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->lockForUpdate()
            ->count();
        
        return $count + 1;
    }
    
    /**
     * Convert bulan (1-12) ke romawi (I-XII)
     */
    private static function getBulanRomawi(int $bulan): string
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
        
        return $romawi[$bulan] ?? 'I';
    }
}
