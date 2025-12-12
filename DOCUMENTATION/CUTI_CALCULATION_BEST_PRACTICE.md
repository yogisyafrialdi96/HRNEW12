# Best Practice: Perhitungan Cuti Efektif dengan Jam Kerja Unit & Libur Nasional

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Arsitektur Solusi](#arsitektur-solusi)
3. [Implementasi Lengkap](#implementasi-lengkap)
4. [Database Schema](#database-schema)
5. [Usage Examples](#usage-examples)
6. [Testing & Validation](#testing--validation)

---

## Overview

**Problem Statement:**
Sistem perhitungan cuti perlu mempertimbangkan:
- ✅ Hari kerja efektif (bukan semua hari dihitung)
- ✅ Hari libur unit (misalnya: Sabtu tidak kerja untuk unit A, tapi kerja untuk unit B)
- ✅ Hari libur nasional (Lebaran, Natalan, dll)
- ✅ Jam kerja flexible per unit (8 jam, 6 jam, shift, dll)

**Solusi:**
Menggunakan **CutiCalculationService** yang mengintegrasikan:
- `jam_kerja_unit` table - Konfigurasi jam kerja per unit
- `libur_nasional` table - Daftar hari libur nasional
- Smart calculation logic yang reusable

---

## Arsitektur Solusi

### Class Diagram
```
┌─────────────────────────────────────────┐
│     CutiCalculationService              │
├─────────────────────────────────────────┤
│ PUBLIC METHODS:                         │
│  + calculateWorkingDays()              │ ← Hitung hari kerja efektif
│  + calculateWorkingHours()             │ ← Hitung jam kerja efektif
│  + calculateMinimumStartDate()         │ ← Min date for h_min_cuti
│  + isEffectiveWorkDay()                │ ← Validasi apakah hari kerja
│                                         │
│ PRIVATE HELPERS:                        │
│  - getUnitWorkDays()                   │
│  - getNationalHolidays()               │
│  - isNationalHoliday()                 │
│  - isWorkDayForUnit()                  │
│  - isWithinWorkingHours()              │
│  - calculateHoursBetween()             │
└─────────────────────────────────────────┘
       ↓             ↓              ↓
    JamKerjaUnit  LiburNasional  TahunAjaran
```

### Data Flow
```
User Input (tanggal_mulai, tanggal_selesai)
           ↓
CutiCalculationService::calculateWorkingDays()
           ↓
├─ Ambil JamKerjaUnit config
├─ Ambil LiburNasional untuk range tanggal
├─ Loop setiap hari dalam range
│  ├─ Skip jika hari libur nasional
│  ├─ Check apakah hari kerja di unit (dari JamKerjaUnit)
│  └─ Count jika working day
└─ Return jumlah hari efektif
           ↓
Komponet mendapat hasil → Update jumlah_hari
```

---

## Implementasi Lengkap

### 1. Setup Service Class ✅

File: `app/Services/CutiCalculationService.php`

**Features:**
```php
// Hitung hari kerja efektif
$service = new CutiCalculationService();
$hariEfektif = $service->calculateWorkingDays(
    tanggalMulai: '2025-12-15',
    tanggalSelesai: '2025-12-19',
    unitId: 5,           // Optional - untuk unit-specific work days
    provinsiId: 1        // Optional - untuk libur nasional regional
);

// Hitung jam kerja efektif (untuk basis jam, bukan hari)
$jamEfektif = $service->calculateWorkingHours(
    tanggalMulai: '2025-12-15',
    jamMulai: '10:00',   // Start from 10 AM (partial day)
    tanggalSelesai: '2025-12-16',
    jamSelesai: '15:00', // End at 3 PM
    unitId: 5
);

// Hitung tanggal mulai minimum untuk h_min_cuti
$minDate = $service->calculateMinimumStartDate(
    hMinCutiHours: 24,   // Minimum 24 jam sebelum
    unitId: 5
);

// Validasi apakah hari tertentu adalah hari kerja
$isWorkDay = $service->isEffectiveWorkDay(
    date: Carbon::parse('2025-12-15'),
    unitId: 5
);
```

### 2. Update Component

File: `app/Livewire/Admin/Cuti/CutiPengajuanIndex.php`

```php
<?php

use App\Services\CutiCalculationService;

class CutiPengajuanIndex extends Component
{
    // ... existing properties ...
    
    private CutiCalculationService $cutiService;
    
    public function mount()
    {
        $this->cutiService = new CutiCalculationService();
    }
    
    /**
     * Update: calculateJumlahHari dengan smart calculation
     */
    public function calculateJumlahHari()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            $this->jumlah_hari = null;
            return;
        }

        try {
            // Ambil unit_id user dari approval setting atau direct
            $user = auth()->user();
            $unitId = null;
            
            if ($user->karyawan && $user->karyawan->jabatanAktif()) {
                $unitId = $user->karyawan->jabatanAktif()->unit_id;
            }
            
            // Ambil provinsi_id dari setting atau user address
            $provinsiId = null; // TODO: ambil dari user/org setting
            
            // Hitung hari kerja efektif menggunakan service
            $this->jumlah_hari = $this->cutiService->calculateWorkingDays(
                $this->tanggal_mulai,
                $this->tanggal_selesai,
                unitId: $unitId,
                provinsiId: $provinsiId
            );
            
            // Calculate estimated remaining leave
            if ($this->cuti_sisa !== null && $this->jumlah_hari) {
                $this->cuti_sisa_estimasi = max(0, $this->cuti_sisa - $this->jumlah_hari);
            }
        } catch (\Exception $e) {
            $this->jumlah_hari = null;
        }
    }
    
    /**
     * Update: loadCutiInfo dengan smart min date calculation
     */
    public function loadCutiInfo()
    {
        try {
            // ... existing logic ...
            
            // Use smart calculation untuk tanggal_mulai_allowed
            if ($this->h_min_cuti && $this->h_min_cuti > 0) {
                $user = auth()->user();
                $unitId = null;
                
                if ($user->karyawan && $user->karyawan->jabatanAktif()) {
                    $unitId = $user->karyawan->jabatanAktif()->unit_id;
                }
                
                // Hitung min date dengan respect ke jam kerja unit & libur
                $minDate = $this->cutiService->calculateMinimumStartDate(
                    $this->h_min_cuti,
                    unitId: $unitId
                );
                
                $this->tanggal_mulai_allowed = $minDate->format('Y-m-d');
            } else {
                $this->tanggal_mulai_allowed = Carbon::now()->format('Y-m-d');
            }
        } catch (\Exception $e) {
            // Silent fail
        }
    }
}
```

### 3. Database Schema

#### `jam_kerja_unit` Table
```sql
CREATE TABLE jam_kerja_unit (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    unit_id BIGINT NOT NULL,
    hari_ke INT NOT NULL,              -- 1=Monday, 2=Tuesday, ..., 7=Sunday
    jam_masuk TIME NOT NULL,           -- e.g., "08:00"
    jam_pulang TIME NOT NULL,          -- e.g., "17:00"
    jam_istirahat VARCHAR(10),         -- e.g., "01:00" (1 hour break)
    is_libur BOOLEAN DEFAULT FALSE,    -- TRUE = hari libur untuk unit ini
    is_full_day BOOLEAN,               -- TRUE = full day entry (vs hourly)
    keterangan TEXT,                   -- Notes
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (unit_id) REFERENCES master_unit(id),
    UNIQUE(unit_id, hari_ke)           -- One config per day per unit
);
```

**Example Data:**
```sql
-- Unit A: Senin-Jumat (kerja), Sabtu-Minggu (libur)
INSERT INTO jam_kerja_unit (unit_id, hari_ke, jam_masuk, jam_pulang, jam_istirahat, is_libur)
VALUES 
(5, 1, '08:00', '17:00', '01:00', FALSE),  -- Monday
(5, 2, '08:00', '17:00', '01:00', FALSE),  -- Tuesday
(5, 3, '08:00', '17:00', '01:00', FALSE),  -- Wednesday
(5, 4, '08:00', '17:00', '01:00', FALSE),  -- Thursday
(5, 5, '08:00', '17:00', '01:00', FALSE),  -- Friday
(5, 6, '08:00', '17:00', '00:00', TRUE),   -- Saturday (libur)
(5, 7, '08:00', '17:00', '00:00', TRUE);   -- Sunday (libur)

-- Unit B: Shift 6 jam (kerja setiap hari)
INSERT INTO jam_kerja_unit (unit_id, hari_ke, jam_masuk, jam_pulang, jam_istirahat, is_libur)
VALUES
(6, 1, '07:00', '13:00', '00:30', FALSE),  -- Monday shift 6 jam
(6, 2, '07:00', '13:00', '00:30', FALSE),  -- Tuesday shift 6 jam
-- ... etc
```

#### `libur_nasional` Table
```sql
CREATE TABLE libur_nasional (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama_libur VARCHAR(255) NOT NULL,      -- e.g., "Lebaran 2025"
    tanggal_libur DATE NOT NULL,           -- Start date
    tanggal_libur_akhir DATE,              -- End date (NULL = single day)
    tipe ENUM('nasional', 'regional', 'lokal'),
    provinsi_id BIGINT,                    -- NULL = all provinces
    is_active BOOLEAN DEFAULT TRUE,
    keterangan TEXT,
    created_by BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (provinsi_id) REFERENCES provinsi(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

**Example Data:**
```sql
-- National holidays
INSERT INTO libur_nasional (nama_libur, tanggal_libur, tipe, is_active)
VALUES
('Tahun Baru', '2025-01-01', 'nasional', TRUE),
('Hari Raya Idul Fitri', '2025-04-10', '2025-04-11', 'nasional', TRUE),
('Lebaran (extended)', '2025-04-10', '2025-04-14', 'nasional', TRUE),
('Natal', '2025-12-25', 'nasional', TRUE);

-- Regional holidays (Jawa Timur only)
INSERT INTO libur_nasional (nama_libur, tanggal_libur, provinsi_id, tipe, is_active)
VALUES
('Nyepi (Bali)', '2025-03-29', 4, 'regional', TRUE);  -- provinsi_id = 4 (Bali)
```

---

## Usage Examples

### Example 1: Hitung Hari Kerja Efektif
```php
// Scenario: Karyawan di Unit A (Senin-Jumat) mau cuti 15-19 Des 2025
// 15 Des = Senin ✓
// 16 Des = Selasa ✓
// 17 Des = Rabu ✓
// 18 Des = Kamis ✓
// 19 Des = Jumat ✓
// Expected: 5 hari

$service = new CutiCalculationService();
$hariEfektif = $service->calculateWorkingDays(
    '2025-12-15',
    '2025-12-19',
    unitId: 5  // Unit A
);
// Result: 5
```

### Example 2: Hitung Hari Kerja (dengan Libur Nasional)
```php
// Scenario: Karyawan cuti 22 Des - 30 Des 2025
// Includes Natal (25 Des) dan Weekend
// 22 Des = Senin ✓
// 23 Des = Selasa ✓
// 24 Des = Rabu ✓
// 25 Des = Kamis (NATAL - LIBUR) ✗
// 26 Des = Jumat ✓
// 27-28 Des = Weekend ✗
// 29 Des = Senin ✓
// 30 Des = Selasa ✓
// Expected: 7 hari

$service = new CutiCalculationService();
$hariEfektif = $service->calculateWorkingDays(
    '2025-12-22',
    '2025-12-30',
    unitId: 5,
    provinsiId: 1  // All provinces (Natal is national)
);
// Result: 7
```

### Example 3: Hitung Jam Kerja (Partial Days)
```php
// Scenario: Partial cuti 15 Des (10:00-17:00) + Full day 16 Des
// 15 Des: 10:00 - 17:00 (7 jam, exclude 1 jam istirahat = 6 jam)
// 16 Des: 08:00 - 17:00 (8 jam)
// Expected: 14 jam

$service = new CutiCalculationService();
$jamEfektif = $service->calculateWorkingHours(
    '2025-12-15',
    '10:00',       // Start at 10 AM
    '2025-12-16',
    '17:00',       // End at 5 PM next day
    unitId: 5
);
// Result: 14.0 (atau 14 jam)
```

### Example 4: Minimum Start Date dengan h_min_cuti
```php
// Scenario: h_min_cuti = 24 jam, current time = Monday 09:00
// Min date = Tuesday 09:00 (24 jam kemudian)
// But if Selasa libur/weekend, skip sampai hari kerja berikutnya

$service = new CutiCalculationService();
$minDate = $service->calculateMinimumStartDate(
    hMinCutiHours: 24,
    unitId: 5
);
// Result: 2025-12-16 (Selasa, 24 jam ke depan)

// Jika sekarang Jumat 16:00:
$minDate = $service->calculateMinimumStartDate(24, unitId: 5);
// Result: 2025-12-22 (Senin, skip weekend)
```

### Example 5: Validasi Hari Kerja
```php
// Scenario: Check apakah 25 Desember adalah hari kerja
// 25 Des = Natal (libur nasional) → FALSE

$service = new CutiCalculationService();
$isWorkDay = $service->isEffectiveWorkDay(
    date: Carbon::parse('2025-12-25'),
    unitId: 5,
    provinsiId: 1
);
// Result: FALSE (karena Natal)

// 26 Des = Jumat (hari kerja) → TRUE
$isWorkDay = $service->isEffectiveWorkDay(
    date: Carbon::parse('2025-12-26'),
    unitId: 5
);
// Result: TRUE
```

---

## Testing & Validation

### Unit Tests Template

```php
<?php

namespace Tests\Unit\Services;

use App\Services\CutiCalculationService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CutiCalculationServiceTest extends TestCase
{
    private CutiCalculationService $service;
    
    protected function setUp(): void
    {
        $this->service = new CutiCalculationService();
    }
    
    /** @test */
    public function it_calculates_working_days_excluding_weekends()
    {
        // Monday to Friday (exclude Sat/Sun)
        $days = $this->service->calculateWorkingDays('2025-12-15', '2025-12-21');
        $this->assertEquals(5, $days);
    }
    
    /** @test */
    public function it_excludes_national_holidays()
    {
        // Dec 22-30 dengan Natal (25 Des)
        $days = $this->service->calculateWorkingDays(
            '2025-12-22',
            '2025-12-30',
            provinsiId: 1
        );
        // Harus < 9 karena Natal & weekend
        $this->assertLessThan(9, $days);
    }
    
    /** @test */
    public function it_respects_unit_specific_work_days()
    {
        // Unit dengan Sabtu kerja vs standard (Sabtu libur)
        $daysWithSaturday = $this->service->calculateWorkingDays(
            '2025-12-13',
            '2025-12-14',
            unitId: 6  // Unit dengan Sabtu kerja
        );
        $this->assertEquals(2, $daysWithSaturday); // Sabtu + Minggu (jika Sunday)
    }
    
    /** @test */
    public function it_calculates_working_hours_with_break_time()
    {
        // 8 jam kerja minus 1 jam istirahat = 7 jam
        $hours = $this->service->calculateWorkingHours(
            '2025-12-15',
            '08:00',
            '2025-12-15',
            '17:00',
            unitId: 5
        );
        $this->assertEquals(8, $hours);
    }
    
    /** @test */
    public function it_calculates_minimum_start_date()
    {
        // 24 jam from now
        $minDate = $this->service->calculateMinimumStartDate(24, unitId: 5);
        
        // Should be future date
        $this->assertTrue($minDate->isFuture());
        
        // Should be working day
        $this->assertTrue(
            $this->service->isEffectiveWorkDay($minDate, unitId: 5)
        );
    }
}
```

---

## Best Practices Checklist

- ✅ **Separation of Concerns**: Logika perhitungan di Service, component hanya call service
- ✅ **Reusability**: Service bisa digunakan di berbagai tempat (API, Command, Scheduled Job)
- ✅ **Flexibility**: Support unit-specific work days dan national/regional holidays
- ✅ **Performance**: Cache unit work days config jika frequently used
- ✅ **Maintainability**: Clear documentation dan well-named methods
- ✅ **Testability**: Service class mudah untuk unit testing tanpa database mocking

---

## Performance Optimization

```php
// Jika frequently called, cache unit work days:

class CutiCalculationService
{
    private array $unitWorkDaysCache = [];
    
    private function getUnitWorkDays(?int $unitId)
    {
        if (!$unitId) {
            return null;
        }
        
        if (isset($this->unitWorkDaysCache[$unitId])) {
            return $this->unitWorkDaysCache[$unitId];
        }
        
        $workDays = JamKerjaUnit::where('unit_id', $unitId)
            ->where('is_libur', false)
            ->get();
        
        $this->unitWorkDaysCache[$unitId] = $workDays;
        
        return $workDays;
    }
}
```

---

## Migration Checklist

- [ ] Create `jam_kerja_unit` table dengan struktur yang benar
- [ ] Create `libur_nasional` table untuk manage holidays
- [ ] Seed data default untuk semua units
- [ ] Seed nasional holidays untuk tahun berjalan
- [ ] Create CutiCalculationService class
- [ ] Update CutiPengajuanIndex component untuk gunakan service
- [ ] Create unit tests untuk service
- [ ] Update documentation
- [ ] Deploy & test di production

---

## Kesimpulan

Dengan menggunakan `CutiCalculationService`, sistem cuti menjadi:
1. **Akurat** - Hitung sesuai konfigurasi unit dan libur nasional
2. **Flexible** - Support berbagai jenis jam kerja dan tipe libur
3. **Maintainable** - Logika terpusat di satu tempat
4. **Reusable** - Bisa digunakan di berbagai context (Component, API, Job, dll)
5. **Testable** - Mudah untuk unit testing

Ini adalah best practice dalam enterprise HR systems untuk memastikan kalkulasi cuti yang akurat dan fair untuk semua karyawan.
