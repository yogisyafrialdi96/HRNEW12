# Quick Reference: Best Practice Perhitungan Cuti Efektif

## 📌 Overview

Sistem cuti HR NEW sekarang menggunakan **smart calculation** yang memperhitungkan:

✅ **Hari Kerja Unit Spesifik** - Setiap unit punya jadwal kerja berbeda  
✅ **Hari Libur Nasional** - Natalan, Lebaran, dll tidak dihitung  
✅ **Hari Libur Regional** - Nyepi hanya untuk Bali, dll  
✅ **Jam Kerja Flexible** - 8 jam, 6 jam, shift, dll  

---

## 🏗️ Arsitektur Solusi

### Komponen Utama

```
CutiCalculationService
├── calculateWorkingDays()          ← Hitung hari efektif
├── calculateWorkingHours()         ← Hitung jam efektif
├── calculateMinimumStartDate()     ← Min date untuk h_min_cuti
└── isEffectiveWorkDay()            ← Validasi hari kerja
```

### Data Source

| Tabel | Fungsi | Contoh |
|-------|--------|--------|
| `jam_kerja_unit` | Konfigurasi jam kerja per unit per hari | Senin-Jumat 08:00-17:00, Sabtu-Minggu libur |
| `libur_nasional` | Hari libur nasional/regional | Lebaran 10-14 April, Natal 25 Des |
| Form Input | Tanggal cuti yg diajukan | 15 Des - 19 Des 2025 |

---

## 📊 Contoh Perhitungan

### Scenario 1: Standard Work Week (Unit A)
```
Unit A: Senin-Jumat (kerja), Sabtu-Minggu (libur)
Cuti: 15-19 Desember 2025

Hasil:
15 Des (Senin) ✓
16 Des (Selasa) ✓
17 Des (Rabu) ✓
18 Des (Kamis) ✓
19 Des (Jumat) ✓

Jumlah Hari: 5 hari
```

### Scenario 2: Dengan Libur Nasional (Natal)
```
Unit A: Senin-Jumat (kerja), Sabtu-Minggu (libur)
Cuti: 22 Des - 30 Des 2025
Libur Nasional: 25 Des (Natal)

Hasil:
22 Des (Senin) ✓
23 Des (Selasa) ✓
24 Des (Rabu) ✓
25 Des (Kamis) ✗ NATAL
26 Des (Jumat) ✓
27-28 Des (Sabtu-Minggu) ✗ WEEKEND
29 Des (Senin) ✓
30 Des (Selasa) ✓

Jumlah Hari: 7 hari (25 Des tidak dihitung)
```

### Scenario 3: Unit dengan Shift 6 Jam (Unit B)
```
Unit B: Setiap hari, 07:00-13:00 (6 jam/hari)
Cuti: 10-11 Desember 2025

Hasil:
10 Des (Rabu): 6 jam ✓
11 Des (Kamis): 6 jam ✓

Jumlah Jam: 12 jam (bukan 16 jam jika standar 8 jam/hari)
Atau: 1.5 hari (12 jam ÷ 8 jam/hari standar)
```

### Scenario 4: Minimum Start Date (h_min_cuti = 24 jam)
```
Kondisi: Jumat 17:00 (end of work day), h_min_cuti = 24 jam
Min Date Calculation:

Jumat 17:00 + 24 jam = Sabtu 17:00 (WEEKEND - SKIP)
                    → Senin 08:00 (next working day)

Hasil: Minimum cuti dapat diajukan pada Senin
```

---

## 🔧 Setup & Configuration

### 1. Database Setup

#### Seed `jam_kerja_unit`
```sql
-- Unit A: Standard (Senin-Jumat)
INSERT INTO jam_kerja_unit (unit_id, hari_ke, jam_masuk, jam_pulang, jam_istirahat, is_libur)
VALUES 
(5, 1, '08:00', '17:00', '01:00', FALSE),  -- Senin
(5, 2, '08:00', '17:00', '01:00', FALSE),  -- Selasa
(5, 3, '08:00', '17:00', '01:00', FALSE),  -- Rabu
(5, 4, '08:00', '17:00', '01:00', FALSE),  -- Kamis
(5, 5, '08:00', '17:00', '01:00', FALSE),  -- Jumat
(5, 6, '08:00', '17:00', '00:00', TRUE),   -- Sabtu (LIBUR)
(5, 7, '08:00', '17:00', '00:00', TRUE);   -- Minggu (LIBUR)

-- Unit B: Shift 6 jam (setiap hari)
INSERT INTO jam_kerja_unit (unit_id, hari_ke, jam_masuk, jam_pulang, jam_istirahat, is_libur)
VALUES
(6, 1, '07:00', '13:00', '00:30', FALSE),  -- Senin
(6, 2, '07:00', '13:00', '00:30', FALSE),  -- Selasa
-- ... dst untuk hari lain
```

#### Seed `libur_nasional`
```sql
-- Nasional holidays
INSERT INTO libur_nasional (nama_libur, tanggal_libur, tanggal_libur_akhir, tipe, is_active)
VALUES
('Tahun Baru', '2025-01-01', NULL, 'nasional', TRUE),
('Lebaran', '2025-04-10', '2025-04-14', 'nasional', TRUE),
('Natal', '2025-12-25', NULL, 'nasional', TRUE);

-- Regional holiday
INSERT INTO libur_nasional (nama_libur, tanggal_libur, provinsi_id, tipe, is_active)
VALUES
('Nyepi (Bali)', '2025-03-29', 4, 'regional', TRUE);
```

### 2. Code Implementation

#### Include Service
```php
use App\Services\CutiCalculationService;

class CutiPengajuanIndex extends Component
{
    private CutiCalculationService $cutiService;
    
    public function mount()
    {
        $this->cutiService = new CutiCalculationService();
    }
}
```

#### Call Service Methods
```php
// Calculate working days
$hariEfektif = $this->cutiService->calculateWorkingDays(
    tanggalMulai: '2025-12-15',
    tanggalSelesai: '2025-12-19',
    unitId: $user->karyawan->jabatanAktif()->unit_id,
    provinsiId: null
);

// Calculate minimum start date
$minDate = $this->cutiService->calculateMinimumStartDate(
    hMinCutiHours: 24,
    unitId: $unitId
);
```

---

## 📈 Performance Considerations

### Optimization Tips

1. **Cache Unit Work Days** (jika frequently accessed)
   ```php
   // Service sudah optimized dengan built-in caching
   // Tapi bisa ditambah app-level cache untuk high-traffic
   ```

2. **Batch Query Holidays**
   ```php
   // Service otomatis batch query holidays dalam range tanggal
   // Jadi efficient untuk perhitungan period panjang
   ```

3. **Index Database**
   ```sql
   CREATE INDEX idx_libur_tanggal 
   ON libur_nasional(tanggal_libur, tanggal_libur_akhir);
   
   CREATE INDEX idx_jam_kerja_unit 
   ON jam_kerja_unit(unit_id, hari_ke);
   ```

---

## ✅ Validation Checklist

Sebelum production, pastikan:

- [ ] Semua unit sudah dikonfigurasi di `jam_kerja_unit`
- [ ] Hari kerja & libur sesuai dengan kebijakan perusahaan
- [ ] Semua libur nasional sudah di-seed untuk tahun berjalan
- [ ] Holiday regional sudah di-setup jika ada
- [ ] Test dengan sample data untuk berbagai scenario
- [ ] Unit tests sudah berjalan pass
- [ ] Performance test untuk high-load usage

---

## 🚀 Usage di Component

```php
// Di CutiPengajuanIndex component

public function calculateJumlahHari()
{
    // 1. Ambil unit user
    $unitId = auth()->user()->karyawan->jabatanAktif()->unit_id;
    
    // 2. Call service dengan parameters
    $this->jumlah_hari = $this->cutiService->calculateWorkingDays(
        $this->tanggal_mulai,
        $this->tanggal_selesai,
        unitId: $unitId,
        provinsiId: 1  // Indonesia
    );
    
    // 3. Result otomatis exclude: weekend, libur unit, libur nasional
}

public function loadCutiInfo()
{
    // 1. Load cuti balance dari DB
    
    // 2. Calculate min date dengan respect ke jam kerja
    $minDate = $this->cutiService->calculateMinimumStartDate(
        hMinCutiHours: 24,
        unitId: $unitId
    );
    
    $this->tanggal_mulai_allowed = $minDate->format('Y-m-d');
}
```

---

## 📚 Dokumentasi Lengkap

Untuk dokumentasi lengkap dengan kode lengkap, lihat:
📄 **File**: `DOCUMENTATION/CUTI_CALCULATION_BEST_PRACTICE.md`

---

## 🆘 FAQ

**Q: Apakah weekend otomatis di-exclude?**  
A: Ya, default adalah Monday-Friday. Jika unit punya custom schedule (e.g., kerja Sabtu), configure di `jam_kerja_unit`.

**Q: Bagaimana dengan shift kerja?**  
A: Gunakan field `jam_masuk` dan `jam_pulang` sesuai shift. Sistem otomatis hitung jam kerja efektif.

**Q: Apakah bisa override untuk karyawan individual?**  
A: Saat ini berbasis unit. Jika perlu individual override, bisa di-enhance dengan tambahan `karyawan_jam_kerja` table.

**Q: Bagaimana jika cuti melewati beberapa hari libur?**  
A: Service otomatis loop setiap hari dan skip jika libur nasional atau hari libur unit.

---

## 🎯 Next Steps

1. ✅ Implement CutiCalculationService
2. ✅ Update component untuk gunakan service
3. 🔲 Seed data `jam_kerja_unit` untuk semua unit
4. 🔲 Seed data `libur_nasional` untuk tahun berjalan
5. 🔲 Unit test untuk berbagai scenario
6. 🔲 Production deployment & monitoring

