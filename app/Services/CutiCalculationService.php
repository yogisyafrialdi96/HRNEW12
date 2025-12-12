<?php

namespace App\Services;

use App\Models\IzinCuti\JamKerjaUnit;
use App\Models\IzinCuti\LiburNasional;
use App\Models\Master\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * Service untuk menghitung jumlah hari cuti/izin yang efektif
 * 
 * Fitur:
 * - Menghitung hari kerja efektif (exclude hari libur unit)
 * - Menghitung hari libur nasional
 * - Menghitung jam kerja efektif (hours-based calculation)
 * - Support kalkulasi berbasis unit atau karyawan
 * 
 * @package App\Services
 */
class CutiCalculationService
{
    /**
     * Hitung total hari kerja efektif antara dua tanggal
     * 
     * Logika:
     * 1. Exclude hari libur unit (is_libur = true)
     * 2. Exclude hari libur nasional
     * 3. Hitung hanya hari kerja (working days)
     * 
     * @param Carbon|string $tanggalMulai
     * @param Carbon|string $tanggalSelesai
     * @param int|null $unitId - ID unit karyawan (opsional)
     * @param int|null $provinsiId - ID provinsi untuk libur nasional (opsional)
     * @return int - Jumlah hari kerja efektif
     * 
     * @example
     * $service = new CutiCalculationService();
     * $hariEfektif = $service->calculateWorkingDays('2025-12-15', '2025-12-19', unitId: 5);
     * // Returns: 4 (exclude weekend dan libur unit)
     */
    public function calculateWorkingDays(
        $tanggalMulai,
        $tanggalSelesai,
        ?int $unitId = null,
        ?int $provinsiId = null
    ): int {
        $mulai = Carbon::parse($tanggalMulai);
        $selesai = Carbon::parse($tanggalSelesai);
        
        // Validasi tanggal
        if ($mulai > $selesai) {
            return 0;
        }
        
        // Ambil konfigurasi unit jika ada
        $unitWorkDays = $this->getUnitWorkDays($unitId);
        
        // Ambil hari libur nasional jika ada
        $nationalHolidays = $this->getNationalHolidays($tanggalMulai, $tanggalSelesai, $provinsiId);
        
        $workingDays = 0;
        $period = CarbonPeriod::create($mulai, $selesai);
        
        foreach ($period as $date) {
            // Skip hari libur nasional
            if ($this->isNationalHoliday($date, $nationalHolidays)) {
                continue;
            }
            
            // Check apakah hari kerja sesuai unit config
            if ($unitWorkDays) {
                if ($this->isWorkDayForUnit($date, $unitWorkDays)) {
                    $workingDays++;
                }
            } else {
                // Default: exclude weekend (Saturday=6, Sunday=0)
                if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                    $workingDays++;
                }
            }
        }
        
        return max(1, $workingDays); // Minimal 1 hari
    }
    
    /**
     * Hitung total jam kerja efektif antara dua tanggal dan waktu
     * 
     * Logika:
     * 1. Hitung berdasarkan jam_kerja_unit
     * 2. Exclude hari libur unit dan nasional
     * 3. Support partial day (jam_masuk - jam_pulang)
     * 
     * @param Carbon|string $tanggalMulai
     * @param string $jamMulai - Format "HH:MM" (opsional, default jam_masuk unit)
     * @param Carbon|string $tanggalSelesai
     * @param string $jamSelesai - Format "HH:MM" (opsional, default jam_pulang unit)
     * @param int|null $unitId
     * @param int|null $provinsiId
     * @return float - Total jam kerja efektif
     * 
     * @example
     * $service = new CutiCalculationService();
     * // Hitung 2 hari penuh kerja (8 jam/hari = 16 jam)
     * $jam = $service->calculateWorkingHours(
     *     '2025-12-15', 
     *     '08:00',
     *     '2025-12-16',
     *     '17:00',
     *     unitId: 5
     * );
     * // Returns: 16 (2 hari x 8 jam)
     */
    public function calculateWorkingHours(
        $tanggalMulai,
        ?string $jamMulai = null,
        $tanggalSelesai,
        ?string $jamSelesai = null,
        ?int $unitId = null,
        ?int $provinsiId = null
    ): float {
        $mulai = Carbon::parse($tanggalMulai);
        $selesai = Carbon::parse($tanggalSelesai);
        
        if ($mulai > $selesai) {
            return 0;
        }
        
        $unitWorkDays = $this->getUnitWorkDays($unitId);
        $nationalHolidays = $this->getNationalHolidays($tanggalMulai, $tanggalSelesai, $provinsiId);
        
        $totalHours = 0;
        $period = CarbonPeriod::create($mulai, $selesai);
        
        foreach ($period as $date) {
            // Skip hari libur
            if ($this->isNationalHoliday($date, $nationalHolidays)) {
                continue;
            }
            
            if ($unitWorkDays) {
                if (!$this->isWorkDayForUnit($date, $unitWorkDays)) {
                    continue;
                }
                
                // Get jam kerja untuk hari tersebut
                $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;
                $workDay = $unitWorkDays->firstWhere('hari_ke', $dayOfWeek);
                
                if (!$workDay) {
                    continue;
                }
                
                // Hitung jam untuk hari ini
                if ($date->isSameDay($mulai) && $date->isSameDay($selesai)) {
                    // Tanggal mulai & selesai sama
                    $hoursForDay = $this->calculateHoursBetween(
                        $jamMulai ?? $workDay->jam_masuk,
                        $jamSelesai ?? $workDay->jam_pulang,
                        $workDay->jam_istirahat
                    );
                } elseif ($date->isSameDay($mulai)) {
                    // Hari pertama - dari jamMulai sampai jam_pulang
                    $hoursForDay = $this->calculateHoursBetween(
                        $jamMulai ?? $workDay->jam_masuk,
                        $workDay->jam_pulang,
                        $workDay->jam_istirahat
                    );
                } elseif ($date->isSameDay($selesai)) {
                    // Hari terakhir - dari jam_masuk sampai jamSelesai
                    $hoursForDay = $this->calculateHoursBetween(
                        $workDay->jam_masuk,
                        $jamSelesai ?? $workDay->jam_pulang,
                        $workDay->jam_istirahat
                    );
                } else {
                    // Hari penuh
                    $hoursForDay = $this->calculateHoursBetween(
                        $workDay->jam_masuk,
                        $workDay->jam_pulang,
                        $workDay->jam_istirahat
                    );
                }
                
                $totalHours += $hoursForDay;
            } else {
                // Default: 8 jam/hari untuk hari kerja
                if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                    $totalHours += 8;
                }
            }
        }
        
        return max(1, $totalHours);
    }
    
    /**
     * Hitung tanggal mulai minimum berdasarkan h_min_cuti
     * 
     * Logika:
     * - Menambahkan h_min_cuti (dalam jam) dari sekarang
     * - Melewati hari libur dan weekend
     * - Return tanggal pertama yang valid
     * 
     * @param int $hMinCutiHours - Minimum jam sebelum cuti dapat diajukan
     * @param int|null $unitId - ID unit untuk check hari kerja
     * @return Carbon - Tanggal mulai terakhir yang diperbolehkan
     * 
     * @example
     * $service = new CutiCalculationService();
     * // Cuti harus diajukan minimum 24 jam sebelumnya
     * $minDate = $service->calculateMinimumStartDate(24, unitId: 5);
     * // Returns: Carbon date 24 jam ke depan (skip weekend/libur)
     */
    public function calculateMinimumStartDate(int $hMinCutiHours, ?int $unitId = null): Carbon
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addHours($hMinCutiHours);
        
        // Jika sudah jam kerja, langsung return
        if ($this->isWithinWorkingHours($now, $unitId)) {
            return $targetTime;
        }
        
        // Find next working day
        $unitWorkDays = $this->getUnitWorkDays($unitId);
        $current = $now->copy();
        
        while (true) {
            $current->addDay();
            
            // Check if it's a working day
            if ($unitWorkDays) {
                if ($this->isWorkDayForUnit($current, $unitWorkDays)) {
                    return $current;
                }
            } else {
                // Default: exclude weekend
                if ($current->dayOfWeek !== 0 && $current->dayOfWeek !== 6) {
                    return $current;
                }
            }
        }
    }
    
    /**
     * Validasi apakah tanggal termasuk periode kerja efektif
     * 
     * @param Carbon $date
     * @param int|null $unitId
     * @param int|null $provinsiId
     * @return bool
     */
    public function isEffectiveWorkDay(
        Carbon $date,
        ?int $unitId = null,
        ?int $provinsiId = null
    ): bool {
        // Check libur nasional
        $nationalHolidays = $this->getNationalHolidays(
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay(),
            $provinsiId
        );
        
        if ($this->isNationalHoliday($date, $nationalHolidays)) {
            return false;
        }
        
        // Check unit work days
        $unitWorkDays = $this->getUnitWorkDays($unitId);
        
        if ($unitWorkDays) {
            return $this->isWorkDayForUnit($date, $unitWorkDays);
        }
        
        // Default: hari kerja biasa (Monday-Friday)
        return $date->dayOfWeek !== 0 && $date->dayOfWeek !== 6;
    }
    
    /**
     * ==================== HELPER METHODS ====================
     */
    
    /**
     * Ambil konfigurasi jam kerja unit untuk seluruh minggu
     * 
     * @param int|null $unitId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    private function getUnitWorkDays(?int $unitId)
    {
        if (!$unitId) {
            return null;
        }
        
        return JamKerjaUnit::where('unit_id', $unitId)
            ->where('is_libur', false) // Exclude libur
            ->orderBy('hari_ke')
            ->get();
    }
    
    /**
     * Ambil daftar hari libur nasional dalam periode tertentu
     * 
     * @param Carbon|string $from
     * @param Carbon|string $until
     * @param int|null $provinsiId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getNationalHolidays($from, $until, ?int $provinsiId = null)
    {
        $query = LiburNasional::where('is_active', true)
            ->whereBetween('tanggal_libur', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($until)->endOfDay()
            ]);
        
        // Filter by provinsi jika ada
        if ($provinsiId) {
            $query->where(function ($q) use ($provinsiId) {
                $q->whereNull('provinsi_id') // All provinces
                  ->orWhere('provinsi_id', $provinsiId);
            });
        }
        
        return $query->get();
    }
    
    /**
     * Check apakah tanggal termasuk hari libur nasional
     * 
     * @param Carbon $date
     * @param \Illuminate\Database\Eloquent\Collection $holidays
     * @return bool
     */
    private function isNationalHoliday(Carbon $date, $holidays): bool
    {
        foreach ($holidays as $holiday) {
            if ($holiday->tanggal_libur_akhir) {
                // Range libur (cth: Lebaran)
                if ($date->between(
                    $holiday->tanggal_libur,
                    $holiday->tanggal_libur_akhir
                )) {
                    return true;
                }
            } else {
                // Single day libur
                if ($date->isSameDay($holiday->tanggal_libur)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check apakah hari adalah hari kerja untuk unit tertentu
     * 
     * @param Carbon $date
     * @param \Illuminate\Database\Eloquent\Collection $unitWorkDays
     * @return bool
     */
    private function isWorkDayForUnit(Carbon $date, $unitWorkDays): bool
    {
        // Convert Carbon dayOfWeek to database hari_ke (1=Monday, 7=Sunday)
        $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;
        
        $workDay = $unitWorkDays->firstWhere('hari_ke', $dayOfWeek);
        
        // Jika tidak ada config untuk hari ini, bukan hari kerja
        return $workDay && !$workDay->is_libur;
    }
    
    /**
     * Check apakah saat ini dalam jam kerja (untuk h_min_cuti)
     * 
     * @param Carbon $time
     * @param int|null $unitId
     * @return bool
     */
    private function isWithinWorkingHours(Carbon $time, ?int $unitId = null): bool
    {
        $unitWorkDays = $this->getUnitWorkDays($unitId);
        
        if (!$unitWorkDays) {
            // Default working hours: 08:00 - 17:00
            return $time->hour >= 8 && $time->hour < 17;
        }
        
        $dayOfWeek = $time->dayOfWeek === 0 ? 7 : $time->dayOfWeek;
        $workDay = $unitWorkDays->firstWhere('hari_ke', $dayOfWeek);
        
        if (!$workDay) {
            return false;
        }
        
        $jamMasuk = Carbon::parse($workDay->jam_masuk);
        $jamPulang = Carbon::parse($workDay->jam_pulang);
        
        return $time->between($jamMasuk, $jamPulang);
    }
    
    /**
     * Hitung jam kerja antara dua waktu (minus jam istirahat)
     * 
     * @param string $jamMulai - Format "HH:MM"
     * @param string $jamSelesai - Format "HH:MM"
     * @param string|null $jamIstirahat - Format "HH:MM" (durasi istirahat)
     * @return float
     */
    private function calculateHoursBetween(
        string $jamMulai,
        string $jamSelesai,
        int|float|string|null $jamIstirahat = null
    ): float {
        $mulai = Carbon::parse("2000-01-01 $jamMulai");
        $selesai = Carbon::parse("2000-01-01 $jamSelesai");
        
        // Jika selesai lebih awal dari mulai, anggap next day
        if ($selesai < $mulai) {
            $selesai->addDay();
        }
        
        $hours = $mulai->diffInMinutes($selesai) / 60;
        
        // Kurangi jam istirahat
        if ($jamIstirahat) {
            $istirahatHours = 0;
            
            // Handle integer (minutes) atau float (hours) atau string (HH:MM)
            if (is_numeric($jamIstirahat)) {
                // Jika integer, assume adalah minutes (dari database)
                // Jika float, assume adalah hours
                $istirahatHours = is_int($jamIstirahat) 
                    ? $jamIstirahat / 60 
                    : (float) $jamIstirahat;
            } elseif (is_string($jamIstirahat) && strpos($jamIstirahat, ':') !== false) {
                // Format HH:MM
                $parts = explode(':', $jamIstirahat);
                $istirahatHours = (int) $parts[0] + ((int) ($parts[1] ?? 0) / 60);
            }
            
            $hours -= $istirahatHours;
        }
        
        return max(0, $hours);
    }
}
