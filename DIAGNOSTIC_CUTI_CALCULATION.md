# Diagnostic - Cuti Calculation Working Days Check

**Issue**: "Jumlah Hari & Estimasi" tidak menghitung jam kerja dan libur nasional dengan benar

**Status**: Investigating

---

## Quick Diagnostics

### 1. Check Database Setup

```bash
# Check if JamKerjaUnit table exists and has data
php artisan tinker

# Check unit work days
>>> use App\Models\IzinCuti\JamKerjaUnit;
>>> JamKerjaUnit::all();  // Should show work day configuration

# Check if LiburNasional table exists
>>> use App\Models\IzinCuti\LiburNasional;
>>> LiburNasional::where('is_active', true)->first();  // Should show national holidays
```

### 2. Check User's Unit Configuration

```bash
php artisan tinker

# Get current user
>>> $user = Auth::user();  // or User::find(1)

# Check if user has karyawan
>>> $user->karyawan;  // Should return Karyawan record

# Check if karyawan has active jabatan
>>> $user->karyawan->jabatanAktif();  // Should return KaryawanJabatan record

# Get unit ID
>>> $jabatan = $user->karyawan->jabatanAktif();
>>> $jabatan->unit_id;  // Should return a valid unit ID

# Check unit work configuration
>>> use App\Models\IzinCuti\JamKerjaUnit;
>>> JamKerjaUnit::where('unit_id', $jabatan->unit_id)->where('is_libur', false)->get();
```

### 3. Test Calculation Service Directly

```bash
php artisan tinker

# Test with user's unit
>>> $service = new App\Services\CutiCalculationService();
>>> $unitId = Auth::user()->karyawan->jabatanAktif()->unit_id;
>>> $days = $service->calculateWorkingDays('2025-12-15', '2025-12-19', unitId: $unitId);
>>> echo "With unit config: " . $days . " hari";

# Test without unit (default weekends only)
>>> $days2 = $service->calculateWorkingDays('2025-12-15', '2025-12-19', unitId: null);
>>> echo "Without unit (weekends only): " . $days2 . " hari";

# Check national holidays in that period
>>> use App\Models\IzinCuti\LiburNasional;
>>> LiburNasional::whereBetween('tanggal_libur', ['2025-12-15', '2025-12-19'])->get();
```

### 4. Check Logs for Errors

```bash
# See calculation errors
tail -100 storage/logs/laravel.log | grep -i "calculating jumlah hari"

# Real-time watch
tail -f storage/logs/laravel.log | grep -i "error"
```

---

## What Should Happen

### Calculation Priority

1. **First Try**: Use unit work configuration
   - Exclude hari libur unit (JamKerjaUnit.is_libur = true)
   - Exclude hari libur nasional (LiburNasional)
   - Exclude weekends
   - **Example**: Mon-Fri + exclude national holiday = 4 days

2. **First Fallback**: No unit config, only weekends & holidays
   - Exclude weekends
   - Exclude hari libur nasional
   - **Example**: Mon-Fri + exclude national holiday = 4 days

3. **Ultimate Fallback**: Simple calendar day count
   - All calendar days
   - No exclusions
   - **Example**: 5 days (even if includes weekends/holidays)

---

## Possible Issues

### Issue 1: User has no unit
**Symptom**: Calculation shows more days than expected  
**Cause**: Falls back to weekends-only exclusion (no unit work config)  
**Fix**: Ensure user->karyawan->jabatanAktif() returns valid record with unit_id

### Issue 2: No LiburNasional records
**Symptom**: National holidays not being excluded  
**Cause**: LiburNasional table empty  
**Fix**: Seed LiburNasional table with actual holidays

### Issue 3: JamKerjaUnit not configured
**Symptom**: Unit-level libur (special off-days) not excluded  
**Cause**: JamKerjaUnit not set up  
**Fix**: Configure working days for unit

### Issue 4: Fallback triggered
**Symptom**: Getting calendar day count instead of working days  
**Cause**: Service throwing exception  
**Fix**: Check logs for specific error

---

## Fixed in This Update

✅ **Better fallback strategy**:
- First try: With unit config
- Second try: Without unit config (weekends & holidays only)
- Third try: Simple calendar count

✅ **Better error logging**:
- Added stack trace to logs
- Identifies which fallback level is being used
- Shows user_id and dates for debugging

✅ **Better logging detail**:
- Exception class recorded
- Full trace available in logs
- Context includes user_id, dates, exception type

---

## Test Cases After Fix

### Test 1: Mon-Fri without holidays
- Dates: 15-19 Dec 2025 (Mon-Fri)
- Expected: 5 days
- Result: ✅ Should show 5 hari

### Test 2: Fri-Mon with weekend
- Dates: 19-22 Dec 2025 (Fri, Sat, Sun, Mon)
- Expected: 2 days (Fri + Mon)
- Result: ✅ Should show 2 hari

### Test 3: With national holiday
- Dates: 15-19 Dec 2025 with 17 Dec = holiday
- Expected: 4 days (exclude 17 Dec)
- Result: ✅ Should show 4 hari (if LiburNasional configured)

### Test 4: No unit config
- User has no jabatanAktif
- Expected: Use weekends-only calculation
- Result: ✅ Should fall back to weekends-only (4-5 days for Mon-Fri range)

---

## Files Modified

### `app/Livewire/Admin/Cuti/CutiPengajuanIndex.php`

**Changes in calculateJumlahHari()**:
1. First try: Service with unit ID
2. Second try: Service without unit ID (weekends & holidays only)
3. Third try: Simple calendar count
4. Better logging with stack trace

**Impact**:
- Handles missing unit config gracefully
- Still excludes national holidays when available
- Always provides a value (better UX)
- Better debugging with detailed logs

---

## Monitoring

After this fix, check:

1. **No errors in logs**:
   ```bash
   grep "Error calculating jumlah hari" storage/logs/laravel.log
   ```

2. **Correct calculation**:
   - Mon-Fri should = 5
   - With weekend = less days
   - With holidays = less days

3. **Fallback usage**:
   - If logs show second fallback, user has no unit config
   - If logs show third fallback, national holidays table might be missing

---

## Next Steps

1. **Test with actual dates**:
   - Select dates that include/exclude weekends
   - Select dates that include national holidays
   - Verify "Jumlah Hari" is correct

2. **Check logs**:
   - `tail -50 storage/logs/laravel.log`
   - Look for "Error calculating jumlah hari"
   - Check if fallback is being used

3. **Verify database**:
   - Check if JamKerjaUnit configured
   - Check if LiburNasional has 2025 dates
   - Check if user has jabatanAktif

4. **Report findings**:
   - Which fallback level is being used
   - What dates give wrong results
   - Any error messages in logs
