# Quick Start: Automatic Retirement Status Update

## What This Does

Automatically changes employee status to "Pensiun Dini" when they reach retirement age (56 years).

## How to Use

### Option 1: Manual Command (Easiest)

Run this command whenever you want to update retired employees:

```bash
php artisan employees:update-retired-status
```

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

### Option 2: Automatic Daily Update (Recommended)

Add this to `app/Console/Kernel.php` in the `schedule()` method:

```php
protected function schedule(Schedule $schedule)
{
    // Update retired employees automatically every day at 2 AM
    $schedule->command('employees:update-retired-status')->daily()->at('02:00');
}
```

Then ensure scheduler is running:
```bash
php artisan schedule:work
```

### Option 3: Background Queue Job

Dispatch the job manually:
```php
// In your controller or wherever needed
use App\Jobs\UpdateRetiredEmployeesStatusJob;

UpdateRetiredEmployeesStatusJob::dispatch();
```

Or add to schedule:
```php
$schedule->job(new UpdateRetiredEmployeesStatusJob)->daily()->at('02:00');
```

## How It Works

**When employee reaches age 56 (retirement age):**

1. ✓ Birth date + 56 years = retirement date
2. ✓ Check if today > retirement date
3. ✓ If yes, automatically update status to "Pensiun Dini" (ID: 3)
4. ✓ Set tgl_berhenti = retirement date

## Example

```
Employee: John Doe
Birth Date: 1968-05-15
Today: 2025-11-13

Calculation:
  Birth Date (1968-05-15) + 56 years = 2024-05-15
  Today (2025-11-13) > 2024-05-15? YES ✓
  
Action:
  Status changed to "Pensiun Dini"
  tgl_berhenti = 2024-05-15
```

## Files Created

| File | Purpose |
|------|---------|
| `app/Models/Employee/Karyawan.php` | Added methods: updateStatusIfRetired(), updateAllRetiredEmployees() |
| `app/Console/Commands/UpdateRetiredEmployeesStatus.php` | Command for manual/scheduled execution |
| `app/Jobs/UpdateRetiredEmployeesStatusJob.php` | Queue job for background processing |

## Checking Results

**View updated employees:**
```sql
SELECT id, nip, full_name, tanggal_lahir, tgl_berhenti, statuskaryawan_id
FROM karyawan 
WHERE statuskaryawan_id = 3 
ORDER BY updated_at DESC 
LIMIT 20;
```

**Check who retires soon:**
```sql
SELECT 
    id, nip, full_name, tanggal_lahir,
    DATE_ADD(tanggal_lahir, INTERVAL 56 YEAR) as retirement_date,
    DATEDIFF(DATE_ADD(tanggal_lahir, INTERVAL 56 YEAR), CURDATE()) as days_until_retirement
FROM karyawan 
WHERE statuskaryawan_id != 3 
  AND tanggal_lahir IS NOT NULL
  AND DATE_ADD(tanggal_lahir, INTERVAL 56 YEAR) <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
ORDER BY retirement_date ASC;
```

## Status ID Reference

| ID | Status | Auto-Updated |
|----|--------|-------------|
| 1 | Aktif | Yes (if retired) |
| 2 | Resign | Yes (if retired) |
| **3** | **Pensiun Dini** | **← Auto-updated to this** |
| 4 | LWP | Yes (if retired) |
| 5 | Tugas Belajar | Yes (if retired) |
| 6 | Habis Kontrak | Yes (if retired) |
| 7 | Meninggal | No (skipped) |

## Troubleshooting

### Command not found
```bash
# Make sure you're in project directory
cd c:\laragon\www\HRNEW12

# Clear cache
php artisan cache:clear
php artisan config:clear

# Try again
php artisan employees:update-retired-status
```

### Check logs for errors
```bash
# View recent logs
tail -f storage/logs/laravel.log
```

### Manual single employee update
```php
// In tinker or controller
$employee = Karyawan::find(123);
$employee->updateStatusIfRetired(); // Returns true if updated
```

## Next Steps

1. ✓ Run command: `php artisan employees:update-retired-status`
2. ✓ Verify results in database
3. ✓ (Optional) Add to scheduler if want automatic updates
4. ✓ Monitor logs: `storage/logs/laravel.log`

---

**Documentation:** Phase5I_Automatic_Retirement_Status_Update.md
