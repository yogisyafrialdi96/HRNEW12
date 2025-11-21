# Phase 5I: Automatic Retirement Status Update

## Overview
Implementasi fitur untuk secara otomatis mengubah status pegawai menjadi "Pensiun Dini" ketika mereka mencapai usia pensiun (56 tahun).

## Features Implemented

### 1. Model Methods (app/Models/Employee/Karyawan.php)

#### Method: updateStatusIfRetired()
```php
public function updateStatusIfRetired()
```
- **Purpose:** Check individual employee and update status if they've reached retirement age
- **Logic:**
  1. Check if employee has tanggal_lahir
  2. Get retirement info
  3. If status is 'retired', update statuskaryawan_id to 3 (Pensiun Dini)
  4. Update tgl_berhenti to retirement date
- **Returns:** bool (true if updated, false otherwise)

#### Method: updateAllRetiredEmployees() (Static)
```php
public static function updateAllRetiredEmployees()
```
- **Purpose:** Batch update all employees who have reached retirement age
- **Logic:**
  1. Get all employees except those already with status 3 (Pensiun Dini)
  2. Filter by those with tanggal_lahir not null
  3. Loop through and call updateStatusIfRetired() for each
  4. Track statistics (updated, already retired, errors)
- **Returns:** Array with counts
  ```php
  [
      'count_updated' => 5,
      'count_already_retired' => 2,
      'count_errors' => 0,
  ]
  ```

### 2. Console Command (app/Console/Commands/UpdateRetiredEmployeesStatus.php)

#### Command: employees:update-retired-status
**Usage:**
```bash
php artisan employees:update-retired-status
```

**Features:**
- Manual trigger for retirement status updates
- Displays results with colored output
- Shows statistics (updated, already retired, errors)
- Error handling with logging

**Output Example:**
```
Starting employee retirement status update...

✓ Update Complete!

Results:
  • Updated to Pensiun: 5
  • Already Pensiun: 2
  • Errors: 0

Successfully updated 5 employee(s) to Pensiun status.
```

### 3. Queued Job (app/Jobs/UpdateRetiredEmployeesStatusJob.php)

#### Job: UpdateRetiredEmployeesStatusJob
- **Purpose:** Run retirement status update asynchronously via queue
- **Benefits:**
  - Non-blocking operation
  - Can be scheduled to run daily
  - Logs all results and errors
  - Retry on failure

**Usage in Scheduled Task:**
```php
// In app/Console/Kernel.php schedule() method:
$schedule->job(new UpdateRetiredEmployeesStatusJob)
    ->daily()
    ->at('02:00');
```

## Database Changes

### Target Table: karyawan
- **Field Updated:** `statuskaryawan_id`
- **New Value:** 3 (Pensiun Dini status)
- **Additional Field:** `tgl_berhenti` (set to retirement date)

### Status ID Reference
| ID | Status | Usage |
|----|--------|-------|
| 1 | Aktif | Active employees |
| 2 | Resign | Resigned employees |
| 3 | Pensiun Dini | **Retired employees (auto-updated)** |
| 4 | LWP | Leave without pay |
| 5 | Tugas Belajar | Educational assignment |
| 6 | Habis Kontrak | Contract ended |
| 7 | Meninggal | Deceased |

## Integration Points

### Option 1: Manual Trigger (Admin)
```bash
# Run command manually whenever needed
php artisan employees:update-retired-status
```

### Option 2: Scheduled Task (Automated Daily)
Add to `app/Console/Kernel.php` in `schedule()` method:
```php
protected function schedule(Schedule $schedule)
{
    // Run daily at 2 AM
    $schedule->command('employees:update-retired-status')->daily()->at('02:00');
}
```

### Option 3: Queued Job (Background Processing)
```php
// Dispatch job manually
UpdateRetiredEmployeesStatusJob::dispatch();

// Or via scheduled task
$schedule->job(new UpdateRetiredEmployeesStatusJob)->daily()->at('02:00');
```

## Implementation Logic

### Retirement Detection
```
1. Get employee's birth date (tanggal_lahir)
2. Add 56 years to get retirement date
3. Check if today's date > retirement date
4. If yes, status = retired
5. Update status to "Pensiun Dini" (ID: 3)
6. Set tgl_berhenti = retirement_date
```

### Example
```
Employee: John Doe
Birth Date: 1968-05-15
Retirement Date: 2024-05-15 (age 56)
Today: 2025-11-13

Status Check: 2025-11-13 > 2024-05-15 ✓ RETIRED
Action: Update statuskaryawan_id = 3
Result: Status changed to "Pensiun Dini"
```

## Error Handling

### Built-in Safeguards
1. **Check tanggal_lahir:** Won't process employees without birth date
2. **Check current status:** Won't update if already "Pensiun Dini" (ID: 3)
3. **Try-catch blocks:** Each employee update is wrapped in try-catch
4. **Logging:** All errors logged to storage/logs/laravel.log
5. **Statistics:** Track successful updates vs errors

### Logging
```php
// Log file: storage/logs/laravel.log

// Success
[2025-11-13 02:00:15] local.INFO: UpdateRetiredEmployeesStatusJob completed {
  "updated": 5,
  "already_retired": 2,
  "errors": 0
}

// Error
[2025-11-13 02:00:16] local.ERROR: Error updating retired employee status: 
Exception message {
  "employee_id": 123,
  "employee_nip": "NIP123456"
}
```

## Testing

### Manual Testing
```bash
# Run command manually
php artisan employees:update-retired-status

# Check results
SELECT id, nip, full_name, statuskaryawan_id, tanggal_lahir 
FROM karyawan 
WHERE statuskaryawan_id = 3 AND tanggal_lahir IS NOT NULL;
```

### Test Scenarios
1. **Employee at retirement date:** Should update ✓
2. **Employee past retirement date:** Should update ✓
3. **Employee before retirement date:** Should not update ✓
4. **Employee already retired:** Should skip (no redundant update) ✓
5. **Employee without birth date:** Should skip ✓
6. **Multiple employees:** All should process correctly ✓

### Verification Query
```sql
-- Check updated employees
SELECT 
    id, 
    nip, 
    full_name, 
    tanggal_lahir,
    DATE_ADD(tanggal_lahir, INTERVAL 56 YEAR) as retirement_date,
    statuskaryawan_id,
    tgl_berhenti,
    updated_at
FROM karyawan 
WHERE statuskaryawan_id = 3 
ORDER BY updated_at DESC 
LIMIT 10;
```

## Setup Instructions

### Step 1: Deploy Code
- Add `UpdateRetiredEmployeesStatus` command to `app/Console/Commands/`
- Add `UpdateRetiredEmployeesStatusJob` to `app/Jobs/`
- Add methods to `Karyawan` model

### Step 2: Create Kernel.php (If Needed)
If `app/Console/Kernel.php` doesn't exist, create it with schedule configuration.

### Step 3: Test Command
```bash
php artisan employees:update-retired-status
```

### Step 4: Setup Scheduler
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('employees:update-retired-status')->daily()->at('02:00');
}
```

### Step 5: Verify Queue Setup
Ensure queue is running if using Job:
```bash
php artisan queue:work
```

## Files Modified

| File | Changes | Status |
|------|---------|--------|
| `app/Models/Employee/Karyawan.php` | Added updateStatusIfRetired() & updateAllRetiredEmployees() | ✅ Complete |
| `app/Console/Commands/UpdateRetiredEmployeesStatus.php` | Created new command | ✅ Complete |
| `app/Jobs/UpdateRetiredEmployeesStatusJob.php` | Created new job | ✅ Complete |

## Configuration

### Retirement Age (Hardcoded)
Currently set to 56 years. To make configurable:
```php
// In config/app.php
'retirement_age' => env('RETIREMENT_AGE', 56),

// In Karyawan model
->addYears(config('app.retirement_age'))
```

### Status ID (Hardcoded)
Currently uses ID 3 for "Pensiun Dini". To make configurable:
```php
// In StatusPegawai model
public static function getPensiunStatusId()
{
    return self::where('nama_status', 'Pensiun Dini')->first()?->id ?? 3;
}

// In Karyawan model
$pensiunStatus = StatusPegawai::getPensiunStatusId();
```

## Benefits

✅ **Automated:** No manual status updates needed
✅ **Accurate:** Based on actual birth date + 56 years calculation
✅ **Safe:** Checks before updating (won't duplicate)
✅ **Logged:** All changes recorded with timestamps
✅ **Flexible:** Can run manually or on schedule
✅ **Traceable:** tgl_berhenti field records actual retirement date
✅ **Efficient:** Batch process with statistics
✅ **Reliable:** Error handling and logging

## Future Enhancements

1. **Configurable Retirement Age**
   - Allow different retirement ages per department/type
   - Store in configuration table

2. **Early Retirement Option**
   - Add early_retirement_date field
   - Check early retirement first

3. **Retirement Notifications**
   - Email notification when employee retires
   - SMS notification to employee

4. **Audit Trail**
   - Create audit_log entry for status changes
   - Track who changed status and when

5. **Batch Job**
   - Add UI to manually trigger updates
   - Show results on admin dashboard

6. **Extended Status**
   - Add "Pensiun Dini" vs "Pensiun" distinction
   - Create separate status for age-based retirement

## References

- Retirement Calculation: Phase 5G (getRetirementInfo method)
- Status Management: Phase 5F (Status Karyawan)
- Milestone System: Phase 5 (Milestone calculations)

---

**Status:** ✅ Complete and Ready for Integration
**Date Completed:** November 13, 2025
**Testing Status:** Ready for manual testing
