# 📚 Dokumentasi Smart Cuti Calculation System

## 🎯 Overview

Sistem perhitungan cuti HR NEW yang mengintegrasikan:
- **Jam Kerja Unit** (jam_kerja_unit) - Per-unit work schedule configuration
- **Hari Libur Nasional** (libur_nasional) - National & regional holidays
- **Smart Calculation** (CutiCalculationService) - Intelligent working days calculation
- **Component Integration** (CutiPengajuanIndex) - Automated UI display

---

## 📋 Dokumentasi Files

### 🚀 Untuk Quick Start
**👉 START HERE:** [`README_CUTI_SMART_CALCULATION.md`](./README_CUTI_SMART_CALCULATION.md)
- 📝 Ringkasan implementasi
- 🔧 Setup instructions
- ✅ Checklist sebelum production
- 🆘 Troubleshooting

**Next Step:** [`CUTI_QUICK_REFERENCE.md`](./CUTI_QUICK_REFERENCE.md)

---

### 📖 Untuk In-Depth Understanding

**[`CUTI_CALCULATION_BEST_PRACTICE.md`](./CUTI_CALCULATION_BEST_PRACTICE.md)** - Architecture Deep Dive
- Complete API documentation
- Data flow examples
- Usage scenarios (5 examples)
- Performance optimization tips
- Database schema detail

**[`CUTI_QUICK_REFERENCE.md`](./CUTI_QUICK_REFERENCE.md)** - Implementation Guide
- Setup & configuration
- Contoh perhitungan (4 scenarios)
- Database schema dengan contoh data
- Usage di component
- FAQ dengan jawaban

**[`CUTI_IMPLEMENTATION_SUMMARY.md`](./CUTI_IMPLEMENTATION_SUMMARY.md)** - Project Summary
- Problem statement & solusi
- Deliverables checklist
- Architecture diagram
- Data flow visualization
- Implementation steps
- Quality assurance checklist
- Knowledge transfer guide

---

### 👨‍💻 Untuk Developer

**Main Service Class:**
```
app/Services/CutiCalculationService.php (400+ lines)
```
**Key Methods:**
- `calculateWorkingDays()` - Hitung hari kerja efektif
- `calculateWorkingHours()` - Hitung jam kerja efektif
- `calculateMinimumStartDate()` - Min date untuk h_min_cuti
- `isEffectiveWorkDay()` - Validasi hari kerja

**Component:**
```
app/Livewire/Admin/Cuti/CutiPengajuanIndex.php
```
**Updates:**
- Added CutiCalculationService import
- Added mount() untuk initialize service
- Updated calculateJumlahHari() dengan smart calculation
- Updated loadCutiInfo() dengan smart min date calculation

**Unit Tests:**
```
tests/Unit/Services/CutiCalculationServiceTest.php (300+ lines)
```
**Coverage:**
- 5+ basic working days tests
- 2+ national holidays tests
- 3+ unit-specific work days tests
- 3+ working hours tests
- 2+ minimum start date tests
- 3+ validation tests
- 3+ edge case tests

---

### 🗄️ Untuk Database Setup

**Migrations:**
```bash
database/migrations/2025_12_10_000001_seed_jam_kerja_unit_default.php
database/migrations/2025_12_10_000002_seed_libur_nasional.php
```

**Default Data:**
- Unit 1: Standard (Mon-Fri: 08:00-17:00)
- Unit 2: Shift (Everyday: 07:00-13:00, 6 jam)
- Libur Nasional 2025-2026

---

## 🗺️ Documentation Roadmap

```
┌─ START HERE ─────────────────────────────┐
│ README_CUTI_SMART_CALCULATION.md         │
│ (5 min read - Overview & Quick Start)    │
└─────────────────┬───────────────────────┘
                  ↓
     ┌───────────────────────┐
     ├─→ CUTI_QUICK_REFERENCE.md
     │   (Setup & Usage, 10 min)
     │
     ├─→ CUTI_CALCULATION_BEST_PRACTICE.md
     │   (Deep dive, 30 min)
     │
     └─→ CUTI_IMPLEMENTATION_SUMMARY.md
         (Project overview, 15 min)
```

---

## 📊 Document Purpose Matrix

| Document | Audience | Purpose | Read Time |
|----------|----------|---------|-----------|
| README_CUTI_SMART_CALCULATION.md | Everyone | Quick overview & setup | 5 min |
| CUTI_QUICK_REFERENCE.md | Implementers, Devs | How to setup & use | 10 min |
| CUTI_CALCULATION_BEST_PRACTICE.md | Developers, Architects | Complete architecture | 30 min |
| CUTI_IMPLEMENTATION_SUMMARY.md | Managers, Team Leads | Project summary & status | 15 min |

---

## 🎓 Learning Path

### For Project Managers
1. Start: `README_CUTI_SMART_CALCULATION.md` (overview)
2. Read: `CUTI_IMPLEMENTATION_SUMMARY.md` (status & checklist)
3. Review: Check list sebelum production

### For Team Leads
1. Start: `README_CUTI_SMART_CALCULATION.md` (overview)
2. Read: `CUTI_IMPLEMENTATION_SUMMARY.md` (architecture)
3. Review: Implementation steps & quality checklist

### For Developers
1. Start: `README_CUTI_SMART_CALCULATION.md` (overview)
2. Read: `CUTI_QUICK_REFERENCE.md` (usage)
3. Study: `CUTI_CALCULATION_BEST_PRACTICE.md` (architecture)
4. Code Review: Service class & Component
5. Run: Unit tests
6. Test: Manual testing di UI

### For QA/Testers
1. Start: `README_CUTI_SMART_CALCULATION.md` (overview)
2. Read: `CUTI_QUICK_REFERENCE.md` (examples)
3. Use: Test cases dari `CutiCalculationServiceTest.php`
4. Create: Test plan berbasis scenarios
5. Execute: UAT dengan berbagai konfigurasi

### For Database Admin
1. Start: `README_CUTI_SMART_CALCULATION.md` (overview)
2. Read: `CUTI_QUICK_REFERENCE.md` (database schema)
3. Run: Migration files (seed data)
4. Verify: Data consistency
5. Backup: Before production deployment

---

## ✅ Setup Checklist

- [ ] Read `README_CUTI_SMART_CALCULATION.md`
- [ ] Run database migrations
  - [ ] `2025_12_10_000001_seed_jam_kerja_unit_default.php`
  - [ ] `2025_12_10_000002_seed_libur_nasional.php`
- [ ] Verify database data
- [ ] Review `CUTI_QUICK_REFERENCE.md`
- [ ] Run unit tests: `php artisan test`
- [ ] Manual testing di UI
- [ ] Test dengan berbagai scenarios
- [ ] Adjust configuration jika perlu
- [ ] Get approval dari stakeholder
- [ ] Deploy ke production

---

## 🔍 File Reference

### Code Files
| File | Lines | Purpose |
|------|-------|---------|
| CutiCalculationService.php | 400+ | Main service class |
| CutiPengajuanIndex.php | 410+ | Livewire component (updated) |
| CutiCalculationServiceTest.php | 300+ | Unit tests |

### Documentation Files
| File | Pages | Purpose |
|------|-------|---------|
| README_CUTI_SMART_CALCULATION.md | 5 | Quick start |
| CUTI_QUICK_REFERENCE.md | 8 | Implementation guide |
| CUTI_CALCULATION_BEST_PRACTICE.md | 15 | Architecture & API docs |
| CUTI_IMPLEMENTATION_SUMMARY.md | 12 | Project summary |

### Migration Files
| File | Action | Status |
|------|--------|--------|
| 2025_12_10_000001_seed_jam_kerja_unit_default.php | Seed jam kerja | ✅ Ready |
| 2025_12_10_000002_seed_libur_nasional.php | Seed libur nasional | ✅ Ready |

---

## 🚀 Quick Start Commands

```bash
# Navigate to project
cd c:\laragon\www\HRNEW12

# Run migrations (setup data)
php artisan migrate

# Verify data
php artisan tinker
  > DB::table('jam_kerja_unit')->count()
  > DB::table('libur_nasional')->count()

# Run unit tests
php artisan test tests/Unit/Services/CutiCalculationServiceTest.php

# Start dev server
php artisan serve

# Test di browser
# http://localhost:8000 → Login → Cuti Management → New Request
```

---

## 📞 Documentation Support

**Q: Mana file yang harus saya baca?**
- Cek "Documentation Purpose Matrix" di atas
- Atau ikuti "Learning Path" sesuai role Anda

**Q: Dokumentasi tidak cukup jelas?**
- Review code comments di service class
- Check unit tests untuk examples
- Hubungi development team

**Q: Bagaimana dengan enhancement/customization?**
- Review `CUTI_IMPLEMENTATION_SUMMARY.md` → "Future Enhancements"
- Service class designed untuk easy extension
- Contact development team untuk discussion

---

## 📈 Version & Status

**Version:** 1.0  
**Release Date:** December 10, 2025  
**Status:** ✅ Ready for Production (after data seeding)  
**Tested:** 20+ test cases  
**Documented:** Comprehensive (4 docs + code comments)

---

## 🎯 Key Features

✅ **Smart Calculation** - Respect unit-specific work days  
✅ **National Holidays** - Auto exclude nasional & regional holidays  
✅ **Flexible Hours** - Support berbagai jenis jam kerja  
✅ **Reusable Service** - Bisa digunakan di berbagai context  
✅ **Comprehensive Tests** - 20+ test cases included  
✅ **Well Documented** - 4 documentation files  

---

## 🏁 Next Steps

1. **For Everyone:** Read `README_CUTI_SMART_CALCULATION.md` (5 min)
2. **For Setup:** Run migrations & verify data
3. **For Developers:** Review service class & run tests
4. **For QA:** Test dengan berbagai scenarios
5. **For Managers:** Review implementation status & checklist

---

**Ready to get started?** → Start with [`README_CUTI_SMART_CALCULATION.md`](./README_CUTI_SMART_CALCULATION.md) 🚀

---

Last Updated: December 10, 2025  
Maintained by: Development Team  
Contact: [Development Team Email]
