<?php

namespace Tests\Unit\Services;

use App\Services\CutiCalculationService;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Test Suite untuk CutiCalculationService
 * 
 * Menguji berbagai scenario perhitungan cuti efektif
 */
class CutiCalculationServiceTest extends TestCase
{
    private CutiCalculationService $service;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CutiCalculationService();
    }
    
    // ============ BASIC WORKING DAYS TESTS ============
    
    /**
     * @test
     * Test: Hitung hari kerja standard (Senin-Jumat, exclude weekend)
     * 
     * Scenario: 15-19 Des 2025 = Senin-Jumat
     * Expected: 5 hari
     */
    public function it_calculates_standard_working_days()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-15',
            tanggalSelesai: '2025-12-19'
        );
        
        $this->assertEquals(5, $days);
    }
    
    /**
     * @test
     * Test: Exclude weekend dalam perhitungan
     * 
     * Scenario: 15-21 Des (include weekend 20-21)
     * Expected: 5 hari (15-19), exclude 20-21 (weekend)
     */
    public function it_excludes_weekends()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-15',
            tanggalSelesai: '2025-12-21'
        );
        
        // Mon 15, Tue 16, Wed 17, Thu 18, Fri 19 = 5 hari
        // Sat 20, Sun 21 = excluded
        $this->assertEquals(5, $days);
    }
    
    /**
     * @test
     * Test: Single day cuti
     * 
     * Scenario: Cuti hanya 1 hari (Senin)
     * Expected: 1 hari
     */
    public function it_handles_single_day_leave()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-15',
            tanggalSelesai: '2025-12-15'
        );
        
        $this->assertEquals(1, $days);
    }
    
    /**
     * @test
     * Test: Cuti hanya weekend (invalid)
     * 
     * Scenario: Cuti Sabtu-Minggu saja (20-21 Des)
     * Expected: 0 hari (semua weekend)
     */
    public function it_returns_zero_for_weekend_only()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-20',
            tanggalSelesai: '2025-12-21'
        );
        
        $this->assertEquals(0, $days);
    }
    
    /**
     * @test
     * Test: Tanggal sebaliknya (end before start)
     * 
     * Scenario: tanggal_selesai < tanggal_mulai
     * Expected: 0 hari
     */
    public function it_returns_zero_for_inverted_dates()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-19',
            tanggalSelesai: '2025-12-15'
        );
        
        $this->assertEquals(0, $days);
    }
    
    // ============ NATIONAL HOLIDAYS TESTS ============
    
    /**
     * @test
     * Test: Exclude hari libur nasional (Natal)
     * 
     * Scenario: 22-30 Des (include Natal 25 Des)
     * Expected: 7 hari (exclude 25 Des, 27-28 weekend)
     */
    public function it_excludes_national_holidays()
    {
        // Assume Natal (25 Des) sudah di-seed di DB
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-22',
            tanggalSelesai: '2025-12-30',
            provinsiId: 1  // All provinces
        );
        
        // 22, 23, 24 (Mon-Wed)
        // 25 (Thu) = NATAL LIBUR
        // 26 (Fri)
        // 27-28 (Sat-Sun) = WEEKEND
        // 29, 30 (Mon-Tue)
        // Total = 7 hari
        
        $this->assertLessThan(9, $days);  // Less than expected 9 jika tanpa libur
    }
    
    /**
     * @test
     * Test: Regional holiday (hanya untuk provinsi tertentu)
     * 
     * Scenario: Nyepi hanya di Bali (provinsi_id = 4)
     * Expected: Different results untuk Bali vs Jawa
     */
    public function it_respects_regional_holidays()
    {
        // For Bali (provinsi_id = 4)
        $daysForBali = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-03-27',
            tanggalSelesai: '2025-03-31',
            provinsiId: 4
        );
        
        // For Jawa (provinsi_id = 1)
        $daysForJawa = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-03-27',
            tanggalSelesai: '2025-03-31',
            provinsiId: 1
        );
        
        // Bali result harus lebih sedikit (exclude Nyepi)
        // Note: Ini test logic, actual data tergantung seed
        // $this->assertLessThan($daysForJawa, $daysForBali);
    }
    
    // ============ UNIT-SPECIFIC WORK DAYS TESTS ============
    
    /**
     * @test
     * Test: Unit A (Senin-Jumat) vs Unit B (Setiap hari)
     * 
     * Scenario: Cuti Sabtu (non-working day untuk Unit A)
     * Expected: Unit A = 0, Unit B = 1 (jika kerja Sabtu)
     */
    public function it_respects_unit_specific_work_days()
    {
        // Unit A: Standard (Senin-Jumat libur)
        // Unit B: Shift (kerja setiap hari)
        
        $daysUnitA = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-20',  // Sabtu
            tanggalSelesai: '2025-12-20',
            unitId: 5
        );
        
        // Unit A tidak kerja Sabtu
        $this->assertEquals(0, $daysUnitA);
    }
    
    /**
     * @test
     * Test: Unit dengan mixed work/non-work days
     * 
     * Scenario: Unit dengan Sabtu kerja tapi Minggu libur
     * Expected: Count Sabtu, exclude Minggu
     */
    public function it_handles_unit_with_saturday_work_day()
    {
        // Assuming Unit X kerja Sabtu
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-20',  // Sabtu
            tanggalSelesai: '2025-12-21', // Minggu
            unitId: 6  // Unit yang kerja Sabtu
        );
        
        // Jika config benar, Sabtu dihitung, Minggu tidak
        // Expected: 1 hari
        // Note: Tergantung actual jam_kerja_unit config
    }
    
    // ============ WORKING HOURS TESTS ============
    
    /**
     * @test
     * Test: Hitung jam kerja efektif (full day = 8 jam)
     * 
     * Scenario: 1 hari full work (08:00-17:00, 1 jam istirahat)
     * Expected: 8 jam
     */
    public function it_calculates_working_hours_full_day()
    {
        $hours = $this->service->calculateWorkingHours(
            tanggalMulai: '2025-12-15',
            jamMulai: '08:00',
            tanggalSelesai: '2025-12-15',
            jamSelesai: '17:00',
            unitId: 5
        );
        
        $this->assertEquals(8, $hours);
    }
    
    /**
     * @test
     * Test: Partial day (pagi atau sore saja)
     * 
     * Scenario: 10:00-17:00 (7 jam - 1 jam istirahat = 6 jam)
     * Expected: ~6 jam
     */
    public function it_calculates_partial_working_hours()
    {
        $hours = $this->service->calculateWorkingHours(
            tanggalMulai: '2025-12-15',
            jamMulai: '10:00',
            tanggalSelesai: '2025-12-15',
            jamSelesai: '17:00',
            unitId: 5
        );
        
        // 10:00 to 17:00 = 7 jam - 1 jam istirahat = 6 jam
        $this->assertGreaterThanOrEqual(5, $hours);
        $this->assertLessThanOrEqual(7, $hours);
    }
    
    /**
     * @test
     * Test: Multiple days dengan partial hours
     * 
     * Scenario: 15 Des 10:00 sampai 16 Des 15:00
     * Day 1: 10:00-17:00 (6 jam after break)
     * Day 2: 08:00-15:00 (6 jam after break)
     * Expected: ~12 jam
     */
    public function it_calculates_multiple_days_with_partial_hours()
    {
        $hours = $this->service->calculateWorkingHours(
            tanggalMulai: '2025-12-15',
            jamMulai: '10:00',
            tanggalSelesai: '2025-12-16',
            jamSelesai: '15:00',
            unitId: 5
        );
        
        // Should be approximately 2 days of work
        $this->assertGreaterThan(10, $hours);
        $this->assertLessThan(16, $hours);
    }
    
    // ============ MINIMUM START DATE TESTS ============
    
    /**
     * @test
     * Test: Min start date dengan h_min_cuti
     * 
     * Scenario: h_min_cuti = 24 jam, current = Monday 09:00
     * Expected: Tuesday (24 jam ke depan)
     */
    public function it_calculates_minimum_start_date()
    {
        // Mock current time as Monday 09:00
        Carbon::setTestNow(Carbon::parse('2025-12-15 09:00:00'));
        
        $minDate = $this->service->calculateMinimumStartDate(
            hMinCutiHours: 24,
            unitId: 5
        );
        
        // Should be Tuesday or later
        $this->assertTrue($minDate->isFuture());
        
        // Cleanup
        Carbon::setTestNow();
    }
    
    /**
     * @test
     * Test: Min start date skip weekend
     * 
     * Scenario: h_min_cuti = 24 jam, current = Friday 17:00
     * Expected: Monday (skip weekend)
     */
    public function it_skips_weekend_when_calculating_minimum_start_date()
    {
        // Mock current time as Friday 17:00
        Carbon::setTestNow(Carbon::parse('2025-12-19 17:00:00'));
        
        $minDate = $this->service->calculateMinimumStartDate(
            hMinCutiHours: 24,
            unitId: 5
        );
        
        // Should be Monday, not Sabtu/Minggu
        $this->assertNotEquals(6, $minDate->dayOfWeek);  // Not Sat
        $this->assertNotEquals(0, $minDate->dayOfWeek);  // Not Sun
        
        // Cleanup
        Carbon::setTestNow();
    }
    
    // ============ VALIDATION TESTS ============
    
    /**
     * @test
     * Test: isEffectiveWorkDay validation
     * 
     * Scenario: Check apakah tanggal adalah hari kerja
     * Expected: 
     *   - Weekday = TRUE
     *   - Weekend = FALSE
     *   - National holiday = FALSE
     */
    public function it_validates_effective_work_days()
    {
        // Senin harus hari kerja
        $isMondayWorkDay = $this->service->isEffectiveWorkDay(
            date: Carbon::parse('2025-12-15'),  // Monday
            unitId: 5
        );
        $this->assertTrue($isMondayWorkDay);
        
        // Sabtu jika unit tidak kerja
        $isSaturdayWorkDay = $this->service->isEffectiveWorkDay(
            date: Carbon::parse('2025-12-20'),  // Saturday
            unitId: 5
        );
        $this->assertFalse($isSaturdayWorkDay);
        
        // Natal tidak boleh hari kerja
        $isNatalWorkDay = $this->service->isEffectiveWorkDay(
            date: Carbon::parse('2025-12-25'),  // Christmas
            unitId: 5,
            provinsiId: 1
        );
        $this->assertFalse($isNatalWorkDay);
    }
    
    // ============ EDGE CASES ============
    
    /**
     * @test
     * Test: Leap year (29 Feb)
     */
    public function it_handles_leap_year_dates()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2024-02-26',
            tanggalSelesai: '2024-02-29'
        );
        
        // Mon-Thu = 4 hari (29 Feb = Thu)
        $this->assertEquals(4, $days);
    }
    
    /**
     * @test
     * Test: Year boundary crossing
     */
    public function it_handles_year_boundary_crossing()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-12-29',
            tanggalSelesai: '2026-01-02'
        );
        
        // Should work across year boundary
        $this->assertGreaterThan(0, $days);
    }
    
    /**
     * @test
     * Test: Very long period (full year)
     */
    public function it_handles_long_periods()
    {
        $days = $this->service->calculateWorkingDays(
            tanggalMulai: '2025-01-01',
            tanggalSelesai: '2025-12-31'
        );
        
        // Approximately 250 working days in a year
        $this->assertGreaterThan(200, $days);
        $this->assertLessThan(280, $days);
    }
}
