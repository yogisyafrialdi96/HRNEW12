# 🧪 TESTING GUIDE: Auto-Sync Status Kontrak

## Overview

**What Changed:** Status kontrak sekarang ALWAYS auto-sync dari tanggal selesai kontrak.

**Why:** Ensure data integrity - status harus selalu match dengan contract end date.

---

## Test Scenarios

### TEST 1: CREATE Kontrak TETAP ⭐

**Setup:** Create new contract
- Jenis Kontrak: **TETAP**
- tglselesai: (disabled, NULL)
- Status button: Select any (will be ignored)

**Steps:**
1. Fill form fields
2. Select Jenis Kontrak: **TETAP**
3. Status radio: Click **"Perpanjangan"** (or any)
4. Save

**Expected Result:**
```
✅ Contract saved
✅ Database: tglselesai = NULL, status = "aktif"
✅ Table: Shows "Tidak terbatas" + Status "Aktif"
✅ NOT "Perpanjangan" (user choice ignored)
```

**If Failed:**
- [ ] Status = "Perpanjangan" → Auto-sync not working
- [ ] Data not saved → Validation error
- [ ] tglselesai not NULL → TETAP logic broken

---

### TEST 2: CREATE Kontrak PKWT (Future Date) ⭐

**Setup:** Create new contract
- Jenis Kontrak: **PKWT**
- tglselesai: **2025-12-31** (masa depan)
- Status button: Select **"Selesai"** (will be ignored)

**Steps:**
1. Fill form fields
2. Select Jenis Kontrak: **PKWT**
3. Set Tanggal Selesai: **2025-12-31**
4. Status radio: Click **"Selesai"**
5. Save

**Expected Result:**
```
✅ Contract saved
✅ Database: tglselesai = 2025-12-31, status = "aktif"
✅ Table: Shows "X hari tersisa" + Status "Aktif"
✅ NOT "Selesai" (user choice ignored, auto-corrected to aktif)
```

**If Failed:**
- [ ] Status = "Selesai" → Auto-sync not working
- [ ] Showing wrong days remaining → Date parsing issue
- [ ] tglselesai not saved → Save logic broken

---

### TEST 3: CREATE Kontrak PKWT (Past Date) ⭐

**Setup:** Create new contract
- Jenis Kontrak: **PKWT**
- tglselesai: **2025-11-01** (already passed)
- Status button: Select **"Aktif"** (will be ignored)

**Steps:**
1. Fill form fields
2. Select Jenis Kontrak: **PKWT**
3. Set Tanggal Selesai: **2025-11-01** (past)
4. Status radio: Click **"Aktif"**
5. Save

**Expected Result:**
```
✅ Contract saved
✅ Database: tglselesai = 2025-11-01, status = "selesai"
✅ Table: Shows "Sudah berakhir (X hari yang lalu)" + Status "Selesai"
✅ NOT "Aktif" (user choice ignored, auto-downgraded to selesai)
```

**Verification:**
- [ ] Toast message: "berhasil disimpan"
- [ ] Status changed from "Aktif" to "Selesai"
- [ ] Table shows red badge "Sudah berakhir"
- [ ] Check server log for: "Auto-syncing status from date"

**If Failed:**
- [ ] Status = "Aktif" → Not detecting past date
- [ ] Table shows positive days → Duration calculation wrong
- [ ] No log message → Logging not working

---

### TEST 4: EDIT - Change Date to Past ⭐

**Setup:** Find existing contract
- Current: tglselesai = 2025-12-31 (future), status = "aktif"

**Steps:**
1. Click **Edit** on this contract
2. Change Tanggal Selesai: **2025-11-01** (to past)
3. Keep Status: **"Aktif"** (or change to other)
4. Click **"Update Kontrak"**

**Expected Result:**
```
✅ Contract updated
✅ Database: tglselesai = 2025-11-01, status = "selesai"
✅ Table: Status changed from "Aktif" to "Selesai"
✅ Shows "Sudah berakhir" (red badge)
```

**Verification:**
- [ ] Toast: "berhasil diedit"
- [ ] Table refreshed with new status
- [ ] Status button in form shows "Selesai"
- [ ] Reload page - changes persist

**If Failed:**
- [ ] Status still = "Aktif" → Edit not triggering auto-sync
- [ ] Table not updated → UI refresh issue
- [ ] Old status in DB → Save not working

---

### TEST 5: EDIT - Change Date to Future ⭐

**Setup:** Find existing expired contract
- Current: tglselesai = 2025-11-01 (past), status = "selesai"

**Steps:**
1. Click **Edit** on this contract
2. Change Tanggal Selesai: **2025-12-31** (to future)
3. Keep Status: **"Selesai"** (or any)
4. Click **"Update Kontrak"**

**Expected Result:**
```
✅ Contract updated
✅ Database: tglselesai = 2025-12-31, status = "aktif"
✅ Table: Status changed from "Selesai" to "Aktif"
✅ Shows "X hari tersisa" (green badge)
```

**Verification:**
- [ ] Status revived from "Selesai" to "Aktif"
- [ ] Days remaining calculated correctly
- [ ] Toast confirms update
- [ ] Duplicate active contract rule still works (other aktif contracts closed)

**If Failed:**
- [ ] Status still = "Selesai" → Not detecting future date
- [ ] Days calculation wrong → Duration formatting issue
- [ ] Other contracts not closed → Duplicate rule broken

---

### TEST 6: EDIT - Change Status Manually (Should Be Ignored) ⭐

**Setup:** Find contract with tglselesai = 2025-12-31 (future), status = "aktif"

**Steps:**
1. Click **Edit**
2. Change Status: Select **"Selesai"** radio
3. **Don't change the date** (leave 2025-12-31)
4. Click **"Update Kontrak"**

**Expected Result:**
```
✅ Contract updated
✅ Database: tglselesai = 2025-12-31, status = "aktif"
✅ Status radio reverts to "Aktif" (not "Selesai")
✅ Your manual selection was IGNORED
```

**Why This Test?**
- Proves system ignores user status choice
- Status ALWAYS comes from date calculation
- This is the core of the fix

**Verification:**
- [ ] User selected "Selesai"
- [ ] But DB has status = "aktif"
- [ ] Form shows "aktif" when re-opened
- [ ] Server log shows: "Auto-syncing status from date"

---

### TEST 7: EDIT TETAP Contract (Set Date) ⭐

**Setup:** Find TETAP contract
- Current: tglselesai = NULL, status = "aktif"

**Steps:**
1. Click **Edit** on TETAP contract
2. Note: Tanggal Selesai field is **ENABLED** (can edit)
3. Set Tanggal Selesai: **2025-12-31**
4. Status: Select any (ignored)
5. Click **"Update Kontrak"**

**Expected Result:**
```
✅ Contract updated
✅ Database: tglselesai = 2025-12-31, status = "aktif"
✅ Not anymore "Tidak terbatas"
✅ Now shows "X hari tersisa"
```

**Why:** TETAP can be converted to limited contract (for resignation)

---

### TEST 8: Duplicate Active Contract Rule (Still Works)

**Setup:** Create 2 contracts for same employee
- Kontrak A: TETAP, auto status = "aktif"
- Kontrak B: PKWT (future), auto status = "aktif"

**Steps:**
1. Create Kontrak A (TETAP)
   - Should be "aktif"
2. Create Kontrak B (PKWT, future)
   - Should auto-sync to "aktif"
   - But should trigger duplicate rule
   - Expected: A closed to "selesai", B stays "aktif"

**Expected Result:**
```
✅ Only 1 contract is "aktif"
✅ Auto-sync + Duplicate rule work together
✅ System closed the other one automatically
```

**Verification:**
- [ ] Kontrak A: status = "selesai" (closed)
- [ ] Kontrak B: status = "aktif" (kept)
- [ ] Both are in same employee
- [ ] Server log shows: contract A auto-closed

---

### TEST 9: Validation Still Works

**Setup:** Try to create/edit with incomplete data

**Steps:**
1. Create contract
2. Leave required fields empty (except tanggal, date handling separate)
3. Save

**Expected Result:**
```
✅ Error toast appears
✅ Lists validation errors
✅ Tanggal Selesai has no error (can be null)
```

**Verification:**
- [ ] Validation error for: Karyawan, Nomor, Golongan
- [ ] NO error for: Tanggal Selesai (can be empty)
- [ ] Form doesn't save with errors
- [ ] Status field value doesn't matter

---

## Summary Checklist

Mark test results:

- [ ] TEST 1: CREATE TETAP → Status "aktif" ✅ PASS
- [ ] TEST 2: CREATE PKWT (future) → Status "aktif" ✅ PASS
- [ ] TEST 3: CREATE PKWT (past) → Status "selesai" ✅ PASS
- [ ] TEST 4: EDIT (change to past) → Status "selesai" ✅ PASS
- [ ] TEST 5: EDIT (change to future) → Status "aktif" ✅ PASS
- [ ] TEST 6: EDIT (manual status ignored) ✅ PASS
- [ ] TEST 7: EDIT TETAP (set date) ✅ PASS
- [ ] TEST 8: Duplicate rule still works ✅ PASS
- [ ] TEST 9: Validation still works ✅ PASS

**Overall Status:**
- ✅ ALL PASS: Feature working correctly
- ❌ ANY FAIL: Report specific test failure

---

## Logging Verification

Check server logs for auto-sync messages:

```bash
# Terminal
tail -f storage/logs/laravel.log

# Look for message:
# "Auto-syncing status from date. tglselesai: 2025-12-31, finalStatus: aktif"
```

---

## Database Verification (Optional)

Check direct database:

```sql
SELECT nomor_kontrak, tglselesai_kontrak, status 
FROM karyawan_kontrak 
ORDER BY updated_at DESC 
LIMIT 5;

# Verify: Status matches date (no inconsistencies)
```

---

## Common Issues & Troubleshooting

| Issue | Cause | Solution |
|-------|-------|----------|
| Status not changing | Livewire cache | Clear cache: `php artisan cache:clear` |
| Form not saving | Validation error | Check form validation errors |
| Old status persists | Browser cache | Hard refresh: Ctrl+Shift+Delete |
| Wrong date calc | Timezone issue | Check config/app.php timezone |
| Log not showing | Logging disabled | Check config/logging.php |

---

**Last Updated:** Nov 12, 2025
**Status:** Ready for Testing
