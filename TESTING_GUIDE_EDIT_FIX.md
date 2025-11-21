# 🧪 TESTING GUIDE - Edit Kontrak Fix

## Setup
- Navigate to: `/admin/kontrak`
- Need: At least 1 existing contract

---

## Test 1: Edit Status - Aktif → Selesai ⭐ PRIORITY

**Steps:**
1. Find a contract with status **"Aktif"**
2. Click **Edit** button
3. In form, scroll to **Status** section
4. Select radio **"Selesai"**
5. Click **"Update Kontrak"** button

**Expected Results:**
- ✅ Toast shows: "Data kontrak berhasil diedit"
- ✅ Modal closes
- ✅ Table refreshes
- ✅ Status column now shows: **"Selesai"**
- ✅ Reload page to confirm persistence

**If Failed:**
- Status reverts to "Aktif" = BUG
- Error toast appears = Check logs
- Modal doesn't close = Validation error?

---

## Test 2: Edit Status - Selesai → Aktif ⭐ PRIORITY

**Steps:**
1. Find a contract with status **"Selesai"**
2. Click **Edit** button
3. In form, select radio **"Aktif"**
4. Click **"Update Kontrak"**

**Expected Results:**
- ✅ Status changes to "Aktif"
- ✅ If another "Aktif" contract exists for same employee:
   - That contract auto-closes to "Selesai"
   - Only 1 contract active per employee ✅

**How to verify multiple contracts:**
1. Check table for same Karyawan name
2. If other contract had "Aktif" → should now be "Selesai"
3. Check logs: Look for `"Contract #X auto-closed..."` message

---

## Test 3: Edit Non-Status Fields (Keep Status Same)

**Steps:**
1. Edit any contract
2. Change: Gaji Pokok, Transport, or Catatan
3. **DO NOT change Status** (leave as is)
4. Click "Update Kontrak"

**Expected Results:**
- ✅ Non-status fields updated ✅
- ✅ Status remains unchanged ✅

---

## Test 4: Edit Tanggal Without Status Change

**Steps:**
1. Edit a contract
2. Change: Tanggal Mulai or Tanggal Selesai
3. Keep status as **"Aktif"**
4. Click "Update Kontrak"

**Expected Results:**
- ✅ Date fields updated ✅
- ✅ Status stays "Aktif" (doesn't auto-sync to date) ✅

---

## Test 5: Create New Contract (Auto-Generate Status)

**Steps:**
1. Click **Create** button
2. Fill form fields
3. Set **Tanggal Selesai = kosong** (empty)
4. Do NOT touch Status radio buttons
5. Click "Simpan Kontrak"

**Expected Results:**
- ✅ Contract saved
- ✅ Status auto-set to "Aktif" (from empty date)

---

## Test 6: Create Contract with Past End Date

**Steps:**
1. Click **Create** button
2. Fill form
3. Set **Tanggal Selesai = yesterday's date**
4. Click "Simpan Kontrak"

**Expected Results:**
- ✅ Contract saved
- ✅ Status auto-set to "Selesai" (from past date)

---

## Test 7: Duplicate Active Contract Close

**Steps:**
1. Have 2 contracts for SAME employee
2. Contract A: status "Aktif"
3. Contract B: status "Selesai"
4. Edit Contract B → Set status "Aktif"
5. Click "Update Kontrak"

**Expected Results:**
- ✅ Contract B status: "Aktif"
- ✅ Contract A auto-changed to: "Selesai"
- ✅ Check logs for: `"Contract #A auto-closed when contract #B set to aktif"`

---

## ✅ All Tests Passed?

If ALL tests pass:
```
🎉 FIX IS WORKING CORRECTLY 🎉
No issues found!
```

---

## ❌ Troubleshooting

### Issue: Status doesn't change after update
**Solution:**
- Clear browser cache (Ctrl+Shift+Delete)
- Try in incognito/private window
- Check Laravel logs: `storage/logs/laravel.log`

### Issue: Toast shows error
**Solution:**
- Check validation rules in form
- Ensure Employee is selected
- Ensure Start Date is set

### Issue: Other contract doesn't auto-close
**Solution:**
- Check logs for auto-close action
- Verify second contract exists for same employee
- Check if second contract was already "Selesai"

### Issue: Page crashes or 500 error
**Solution:**
- Check `storage/logs/laravel.log` for exceptions
- Clear cache: `php artisan cache:clear`
- Restart server

---

## ℹ️ Log Checking

To monitor auto-close actions:

```bash
# Terminal 1: Tail logs
tail -f storage/logs/laravel.log

# Terminal 2: Perform actions
# Edit contract to "Aktif" → check logs for auto-close message
```

Expected log message:
```
[2025-11-12 08:00:00] local.INFO: Contract #5 auto-closed when contract #3 set to aktif
```

---

## 📋 Test Completion Checklist

- [ ] Test 1: Aktif → Selesai ✅ PASSED
- [ ] Test 2: Selesai → Aktif ✅ PASSED  
- [ ] Test 3: Other fields update ✅ PASSED
- [ ] Test 4: Date change (no status sync) ✅ PASSED
- [ ] Test 5: Create with empty date ✅ PASSED
- [ ] Test 6: Create with past date ✅ PASSED
- [ ] Test 7: Duplicate auto-close ✅ PASSED

**Overall Result:** ✅ ALL TESTS PASSED / ❌ SOME TESTS FAILED

---

## Report Issues
If any test fails, provide:
1. Which test failed (number + name)
2. What happened (actual vs expected)
3. Error message (if any)
4. Screenshot (if helpful)
5. Browser console errors (F12)

---

**Last Updated:** Nov 12, 2025
**Status:** Ready for Testing
