# Phase 5H Testing Guide: Retirement-Aware Milestone Display

## Overview
This guide provides comprehensive testing procedures for Phase 5H - Milestone Display Limited to Retirement Date.

---

## Test Environment Setup

### Prerequisites
- ✅ Laravel application running (php artisan serve)
- ✅ Database populated with test data
- ✅ All migrations executed
- ✅ Browser with developer console available

### Test Data Requirements
- Employees with various birth dates (young, middle, near retirement, already retired)
- Employees with and without birth dates
- Employees with and without contracts
- Mix of status types

---

## Test Categories

## 1️⃣ UNIT TESTS (Code Level)

### Test 1.1: isMilestoneBeforeRetirement() Method Exists
```
File: app/Models/Employee/Karyawan.php
Line: 272-288

Test:
  - Method exists
  - Method is public
  - Method accepts $milestoneDate parameter
  - Method returns boolean
  
Expected: ✅ PASS
```

### Test 1.2: Method Returns True for Before Retirement
```php
$employee = Karyawan::find(1); // Born 1980, retires 2036
$beforeRetirement = Carbon::parse('2030-01-01');
$result = $employee->isMilestoneBeforeRetirement($beforeRetirement);

Expected: true ✅
```

### Test 1.3: Method Returns False for After Retirement
```php
$employee = Karyawan::find(1); // Born 1980, retires 2036
$afterRetirement = Carbon::parse('2040-01-01');
$result = $employee->isMilestoneBeforeRetirement($afterRetirement);

Expected: false ✅
```

### Test 1.4: Method Returns True if No Retirement Info
```php
$employee = Karyawan::find(1); // No birth date (tanggal_lahir = null)
$anyDate = Carbon::parse('2030-01-01');
$result = $employee->isMilestoneBeforeRetirement($anyDate);

Expected: true ✅ (safe default)
```

### Test 1.5: Blade Template Uses Method
```
File: resources/views/livewire/admin/karyawan/masakerja/index.blade.php

Search for: isMilestoneBeforeRetirement
Expected: Found in all 7 milestone columns (5, 10, 15, 20, 25, 30, 35) ✅
```

---

## 2️⃣ INTEGRATION TESTS (Component Level)

### Test 2.1: Page Loads Without Errors
```
Navigate to: /admin/karyawan/masakerja
Expected: 
  - Page loads successfully ✅
  - No JavaScript console errors ✅
  - No PHP errors in logs ✅
```

### Test 2.2: Data Loads Correctly
```
Check: Livewire component renders
Expected:
  - Table appears ✅
  - Filters load ✅
  - Employee data displays ✅
  - No "undefined" or null values ✅
```

### Test 2.3: Retirement Info Displays
```
Check: Awal Kerja column
Expected:
  - Shows contract start date ✅
  - Shows "Pensiun:" label ✅
  - Shows retirement date ✅
```

### Test 2.4: All 7 Milestone Columns Render
```
Check: Table columns
Expected:
  - Column headers: 5T, 10T, 15T, 20T, 25T, 30T, 35T ✅
  - All columns contain data or dashes ✅
  - No missing columns ✅
```

---

## 3️⃣ FUNCTIONAL TESTS (Feature Level)

### Test 3.1: Young Employee (Age 30, Retires 2050)
```
Test Employee: Born 1993-01-01, Retires 2049-01-01
First Contract: 2015-01-01

Expected Display:
  5T  (2020): ✅ Show badge "Achieved"
  10T (2025): ✅ Show badge "Upcoming" 
  15T (2030): ✅ Show badge "Upcoming"
  20T (2035): ✅ Show badge "Upcoming"
  25T (2040): ✅ Show badge "Upcoming"
  30T (2045): ✅ Show badge "Upcoming"
  35T (2050): ❌ Show "-" (after retirement 2049)
```

### Test 3.2: Employee Near Retirement (Age 54, Retires 2027)
```
Test Employee: Born 1970-01-01, Retires 2026-01-01
First Contract: 1995-01-01

Expected Display:
  5T  (2000): ✅ Show badge "Achieved"
  10T (2005): ✅ Show badge "Achieved"
  15T (2010): ✅ Show badge "Achieved"
  20T (2015): ✅ Show badge "Achieved"
  25T (2020): ✅ Show badge "Achieved"
  30T (2025): ✅ Show badge "Achieved"
  35T (2030): ❌ Show "-" (after retirement 2026)

Awal Kerja shows: Mulai: 1995-01-01, Pensiun: 2026-01-01 ✅
Masa Kerja shows: 31 tahun..., Pensiun dalam: 2 tahun... ✅
```

### Test 3.3: Just Retired Employee (Age 56, Retired 2023)
```
Test Employee: Born 1967-01-01, Retired 2023-01-01
Current Date: 2025-01-01

Expected Display:
  All milestone columns show: ❌ "-" (all in past/after retirement)
  
Awal Kerja: Pensiun: 2023-01-01 ✅
Masa Kerja: Shows "sudah pensiun" or retirement info ✅
```

### Test 3.4: Employee Without Birth Date
```
Test Employee: tanggal_lahir = NULL
First Contract: 2015-01-01

Expected Display:
  5T  (2020): ✅ Show badge (shows all as fallback)
  10T (2025): ✅ Show badge
  15T (2030): ✅ Show badge
  20T (2035): ✅ Show badge
  25T (2040): ✅ Show badge
  30T (2045): ✅ Show badge
  35T (2050): ✅ Show badge

Retirement info: Shows nothing or "N/A" ✅
```

### Test 3.5: Employee Without Contracts
```
Test Employee: No records in KaryawanKontrak table
Birth Date: 1980-01-01

Expected Display:
  All milestone columns: ❌ "-" (no contract date to calculate from)
  Awal Kerja: ❌ "-"
  Masa Kerja: ❌ "-" or "Belum ada kontrak"
```

---

## 4️⃣ UI/UX TESTS (Visual)

### Test 4.1: Visual Distinction
```
Check: Can you visually distinguish between:
  - Visible milestones (dark text)
  - Hidden milestones (light gray text)
  
Expected: ✅ Clear visual difference
  Visible: text-gray-800 or similar
  Hidden:  text-gray-300 (much lighter)
```

### Test 4.2: Badge Visibility
```
Check: Status badges for visible milestones
Expected:
  - Badges have color ✅
  - Badge text readable ✅
  - Hidden milestones don't have badges ✅
  - Gray dashes where hidden ✅
```

### Test 4.3: Alert Dots
```
Check: Animated alert dots on upcoming milestones
Expected:
  - Alert appears only for visible milestones ✅
  - Alert is animated (pulsing) ✅
  - Alert appears before retirement cutoff ✅
  - No alert after retirement milestone ✅
```

### Test 4.4: Responsive Design
```
Test on: Mobile (375px), Tablet (768px), Desktop (1440px)
Expected:
  - Table scrolls horizontally on mobile ✅
  - Columns stack appropriately ✅
  - Gray dashes render correctly ✅
  - No overlapping text ✅
```

### Test 4.5: Hover States
```
Check: Hover effects on table cells
Expected:
  - Rows highlight on hover ✅
  - Milestone badges show tooltips (if any) ✅
  - Gray dashes remain visible ✅
```

---

## 5️⃣ FILTER COMBINATION TESTS

### Test 5.1: Milestone Filter + Retirement Display
```
Filter: Milestone Filter = 35 Tahun
Employee: Retiring before 35 years

Expected:
  - Employee appears in filtered list ✓ (matches 35T milestone)
  - 35T column shows "-" (after retirement) ✓
```

### Test 5.2: Status Filter + Retirement Display
```
Filter: Status = Aktif
Employee: Aktif but retiring soon

Expected:
  - Employee appears (matches Aktif status) ✓
  - Unreachable milestones show "-" ✓
```

### Test 5.3: Unit Filter + Retirement Display
```
Filter: Unit = Engineering, Milestone = 10T
Employee: In Engineering, retiring before 10T

Expected:
  - Employee not in list (retires before 10T) ✓
  - Correct retirement boundary respected ✓
```

### Test 5.4: Search + Retirement Display
```
Search: "John"
Result: Employee named John retiring in 2027

Expected:
  - John appears in results ✓
  - His milestone display shows retirement boundary ✓
```

### Test 5.5: Sort + Retirement Display
```
Sort: By Name (A-Z)
Expected:
  - Employees sorted alphabetically ✓
  - Each employee shows correct retirement milestone boundaries ✓
```

---

## 6️⃣ EDGE CASE TESTS

### Test 6.1: Milestone Exactly on Retirement Date
```
Employee: Birth 1968-01-01 (retires 2024-01-01)
Milestone: 30 years (2024-01-01)

Expected: Show "-" (not "before" retirement) ✅
Comparison: Uses .isBefore() which is < not <=
```

### Test 6.2: Milestone One Day Before Retirement
```
Employee: Birth 1968-01-01 (retires 2024-01-01)
Milestone: 30 years (2023-12-31)

Expected: Show milestone badge ✅
```

### Test 6.3: Retirement Date Calculation
```
Birth: 1968-03-15
Retirement Should Be: 2024-03-15 (exactly age 56)

Test: Parse Awal Kerja column, verify date shown
Expected: 2024-03-15 ✅
```

### Test 6.4: First Contract Much Earlier Than Birth
```
Employee: Born 1968, First Contract 1980 (age 12)
Expected: Still uses birth date for retirement, not contract date ✅
```

### Test 6.5: Multiple Contracts
```
Employee: Multiple KaryawanKontrak records
Expected: Uses FIRST contract date for milestone calculation ✅
```

---

## 7️⃣ PERFORMANCE TESTS

### Test 7.1: Page Load Time
```
Load: /admin/karyawan/masakerja
Expected: < 500ms ✅

Check DevTools:
  - DOMContentLoaded: < 1s ✅
  - Load event: < 2s ✅
```

### Test 7.2: Filter Application
```
Apply: Milestone Filter = 10 Tahun
Expected: Updates in < 200ms ✅

Check: Livewire response time
```

### Test 7.3: Search Performance
```
Search: "john"
Expected: Results in < 300ms ✅
```

### Test 7.4: Calculation Overhead
```
Compare page load:
  - With retirement filtering ✅
  - Without retirement filtering ✅
  
Difference should be negligible (< 50ms) ✅
```

---

## 8️⃣ DATABASE/DATA TESTS

### Test 8.1: Database Queries
```
Enable Query Log:
  DB::enableQueryLog();

Navigate to masa kerja page

Check:
  - No N+1 queries ✅
  - Relationships eager-loaded ✅
  - Proper use of indexes ✅
```

### Test 8.2: Data Consistency
```
Check: Employee data against database
  - Birth dates match ✅
  - Contract dates match ✅
  - Calculated retirement dates consistent ✅
  - Milestone dates calculated correctly ✅
```

### Test 8.3: Missing Data Handling
```
Test with:
  - NULL birth date ✅ (should show all milestones)
  - NULL contract date ✅ (should show "-")
  - No contracts ✅ (should show "-")
  - NULL status ✅ (should show "Lainnya")
```

---

## 9️⃣ BROWSER COMPATIBILITY TESTS

### Test 9.1: Chrome/Chromium
```
Browser: Latest Chrome
Expected: All features working ✅
  - Dates render correctly ✅
  - Animations smooth ✅
  - No console errors ✅
```

### Test 9.2: Firefox
```
Browser: Latest Firefox
Expected: All features working ✅
```

### Test 9.3: Safari
```
Browser: Latest Safari
Expected: All features working ✅
```

### Test 9.4: Mobile Chrome
```
Browser: Chrome on mobile
Expected: Responsive layout ✅
```

---

## 🔟 SECURITY TESTS

### Test 10.1: SQL Injection
```
Search: '; DROP TABLE karyawan; --
Expected: 
  - Query sanitized ✅
  - No error in response ✅
  - Table not affected ✅
```

### Test 10.2: XSS Prevention
```
Employee Name: <script>alert('test')</script>
Expected:
  - Script doesn't execute ✅
  - Text displayed as literal ✅
```

### Test 10.3: Filter Injection
```
Unit Filter: Invalid ID (999)
Expected:
  - No error ✅
  - Returns empty results ✅
  - No data leak ✅
```

---

## Test Execution Checklist

### Pre-Test
- [ ] Fresh database backup created
- [ ] All migrations run successfully
- [ ] Test data populated
- [ ] Browser cache cleared
- [ ] Developer tools open
- [ ] Error logs cleared

### During Tests
- [ ] Document each test result
- [ ] Take screenshots of failures
- [ ] Note timing/performance
- [ ] Check for console errors
- [ ] Verify database queries

### Post-Test
- [ ] Summarize results
- [ ] Create bug report for failures
- [ ] Recommend fixes
- [ ] Restore database from backup
- [ ] Update documentation with findings

---

## Test Result Template

```
Test: [Test Name]
Date: [YYYY-MM-DD]
Tester: [Name]
Browser: [Browser Name & Version]
Environment: [Dev/Staging/Production]

Test Steps:
1. [Step 1]
2. [Step 2]
3. [Step 3]

Expected Result: [Description]
Actual Result: [Description]

Status: ✅ PASS / ❌ FAIL / ⚠️ PARTIAL

Notes: [Any observations]
Screenshots: [Attached if failed]
```

---

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| All milestones show "-" | No birth date or retirement in past | Verify tanggal_lahir field populated |
| Gray dashes too light | CSS not loading | Clear browser cache |
| Alert dot not showing | Hidden milestone or > 30 days away | Check if milestone within 30 days AND visible |
| Wrong retirement date | Birth date calculation error | Verify birth date format (Y-m-d) |
| Page slow to load | Too many employees | Add pagination or limit query |

---

## Success Criteria

**Phase 5H is successful if:**

✅ Unit Tests: 100% pass
✅ Integration Tests: 100% pass
✅ Functional Tests: All scenarios correct
✅ UI/UX Tests: Visually correct and accessible
✅ Performance: < 500ms page load
✅ Security: No vulnerabilities found
✅ Browser Compatibility: Works on Chrome, Firefox, Safari, Mobile
✅ Database: No errors or data issues

---

## Sign-Off

```
Phase 5H Testing: _________________
Date: _________________
Tester: _________________
Status: ✅ APPROVED / ❌ NEEDS FIXES

Issues Found: ___
Issues Fixed: ___
Issues Deferred: ___
```

---

## Next Steps After Testing

1. **If All Pass:** Deploy to production
2. **If Issues Found:** Create bug tickets and re-test
3. **If Performance Issues:** Optimize queries and cache
4. **If UX Issues:** Adjust styling and layout
5. **Post-Deployment:** Monitor logs for errors

---

**End of Testing Guide - Phase 5H**
