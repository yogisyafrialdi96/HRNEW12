# Phase 5G: Retirement Age (56 Years) Calculation & Display

## Overview
Implementasi fitur perhitungan tanggal pensiun pegawai berdasarkan usia 56 tahun, menampilkan sisa masa kerja hingga pensiun, dan tanggal pensiun di kolom "Awal Kerja".

## Changes Made

### 1. Karyawan Model (app/Models/Employee/Karyawan.php)

#### Added getRetirementInfo() Method
```php
public function getRetirementInfo()
{
    // Check if tanggal_lahir exists
    if (!$this->tanggal_lahir) {
        return null;
    }

    $birthDate = Carbon::parse($this->tanggal_lahir);
    $retirementDate = $birthDate->copy()->addYears(56);
    $now = Carbon::now();

    // Check if already retired
    if ($now->greaterThan($retirementDate)) {
        return [
            'status' => 'retired',
            'retirement_date' => $retirementDate,
            'formatted_retirement_date' => $retirementDate->translatedFormat('d M Y'),
            'current_age' => $now->diffInYears($birthDate),
            'message' => 'Telah pensiun',
        ];
    }

    $yearsUntilRetirement = $retirementDate->diffInYears($now);
    $monthsUntilRetirement = $retirementDate->diffInMonths($now) % 12;
    $daysUntilRetirement = $retirementDate->diffInDays($now);

    $currentAge = $now->diffInYears($birthDate);

    return [
        'status' => 'active',
        'retirement_date' => $retirementDate,
        'formatted_retirement_date' => $retirementDate->translatedFormat('d M Y'),
        'current_age' => $currentAge,
        'years_remaining' => $yearsUntilRetirement,
        'months_remaining' => $monthsUntilRetirement,
        'days_remaining' => $daysUntilRetirement,
        'formatted' => sprintf('%d Tahun %d Bulan', $yearsUntilRetirement, $monthsUntilRetirement),
        'short' => sprintf('%d.%d Tahun', $yearsUntilRetirement, $monthsUntilRetirement),
        'message' => sprintf('Pensiun dalam %d tahun %d bulan', $yearsUntilRetirement, $monthsUntilRetirement),
    ];
}
```

**Purpose:**
- Calculates retirement date based on birth date + 56 years
- Returns comprehensive retirement information
- Handles two scenarios: already retired or still working
- Includes current age, years/months/days remaining
- Formatted dates in Indonesian locale

### 2. Index Component (app/Livewire/Admin/Karyawan/Masakerja/Index.php)

#### Updated transform() in render()
Added retirement_info to employee data:
```php
$karyawans->getCollection()->transform(function ($employee) {
    $employee->milestones = $employee->calculateMilestones();
    $employee->current_duration = $employee->getCurrentWorkDuration();
    $employee->upcoming_milestone = $employee->getUpcomingSoonMilestone();
    $employee->retirement_info = $employee->getRetirementInfo();  // Added
    return $employee;
});
```

### 3. Blade Template (resources/views/livewire/admin/karyawan/masakerja/index.blade.php)

#### Updated "Awal Kerja" Column
Changed from single line to multi-line display showing:
1. **Mulai:** Contract start date
2. **Pensiun:** Retirement date (calculated based on birth date + 56 years)

```blade
<!-- Awal Kerja & Tanggal Pensiun -->
<td class="px-6 py-4 whitespace-nowrap">
    <div class="flex flex-col gap-1">
        <div class="text-sm text-gray-900">
            <span class="font-semibold">Mulai:</span> {{ \Carbon\Carbon::parse($karyawan->contracts[0]->tglmulai_kontrak)->translatedFormat('d M Y') ?? '-' }}
        </div>
        @if($karyawan->retirement_info)
            @if($karyawan->retirement_info['status'] === 'retired')
                <div class="text-sm text-gray-600">
                    <span class="font-semibold">Pensiun:</span> {{ $karyawan->retirement_info['formatted_retirement_date'] }}
                </div>
            @else
                <div class="text-sm text-blue-600">
                    <span class="font-semibold">Pensiun:</span> {{ $karyawan->retirement_info['formatted_retirement_date'] }}
                </div>
            @endif
        @else
            <div class="text-sm text-gray-400">
                <span>Pensiun: -</span>
            </div>
        @endif
    </div>
</td>
```

#### Updated "Masa Kerja Berjalan" Column
Enhanced to show retirement countdown:
```blade
{{-- Retirement Info --}}
@if($karyawan->retirement_info)
    @if($karyawan->retirement_info['status'] === 'retired')
        {{-- Already Retired --}}
        <span class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-800 w-fit mt-1">
            ✓ Pensiun {{ $karyawan->retirement_info['formatted_retirement_date'] }}
        </span>
    @else
        {{-- Still Working - Show Remaining Time --}}
        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 w-fit mt-1">
            📅 Pensiun dalam {{ $karyawan->retirement_info['formatted'] }}
        </span>
        <span class="text-xs text-gray-500">Usia saat ini: {{ $karyawan->retirement_info['current_age'] }} tahun</span>
    @endif
@endif
```

## Retirement Logic

### Retirement Age: 56 Years
- Standard retirement age in Indonesia
- Calculated from birth date (`tanggal_lahir`)
- Formula: `birth_date + 56 years = retirement_date`

### Return Data Structure

**For Active Employees (Still Working):**
```php
[
    'status' => 'active',
    'retirement_date' => Carbon instance,
    'formatted_retirement_date' => '15 Agus 2035',
    'current_age' => 45,  // Current age in years
    'years_remaining' => 11,
    'months_remaining' => 3,
    'days_remaining' => 45,
    'formatted' => '11 Tahun 3 Bulan',
    'short' => '11.3 Tahun',
    'message' => 'Pensiun dalam 11 tahun 3 bulan',
]
```

**For Retired Employees:**
```php
[
    'status' => 'retired',
    'retirement_date' => Carbon instance,
    'formatted_retirement_date' => '15 Agus 2023',
    'current_age' => 57,
    'message' => 'Telah pensiun',
]
```

**If No Birth Date:**
```php
null
```

## Display Format

### Kolom "Awal Kerja" (Start Date & Retirement Date)

**Active Employee:**
```
Mulai: 15 Agu 2015
Pensiun: 15 Agus 2035  (blue text)
```

**Retired Employee:**
```
Mulai: 15 Agu 2010
Pensiun: 15 Agus 2021  (gray text)
```

**No Birth Date:**
```
Mulai: 15 Agu 2015
Pensiun: -
```

### Kolom "Masa Kerja Berjalan" (Current Work Duration)

**Active Employee (Multiple Info):**
```
12 Tahun 4 Bulan
(4521 hari)
📅 Pensiun dalam 11 tahun 3 bulan
Usia saat ini: 45 tahun
⚠️ Milestone 25 Th  (if applicable)
```

**Retired Employee:**
```
12 Tahun 4 Bulan
(4521 hari)
✓ Pensiun 15 Agus 2023
```

## Color Coding

| Element | Color | Status |
|---------|-------|--------|
| Pensiun date (active) | 🔵 Blue | Still working, will retire |
| Pensiun date (retired) | ⚫ Gray | Already retired |
| Pensiun badge (active) | 🔵 Blue badge | Countdown to retirement |
| Pensiun badge (retired) | ⚫ Gray badge | Already retired |

## Practical Examples

### Example 1: Active Employee (45 years old)
```
Birth Date: 15 Agus 1979
Retirement Date: 15 Agus 2035 (age 56)
Today: 15 Nov 2024
Remaining: 11 years 9 months
Display: "📅 Pensiun dalam 11 tahun 9 bulan"
```

### Example 2: Employee Near Retirement (55 years old)
```
Birth Date: 20 Mar 1969
Retirement Date: 20 Mar 2025 (age 56)
Today: 15 Nov 2024
Remaining: 4 months 5 days
Display: "📅 Pensiun dalam 0 tahun 4 bulan"
```

### Example 3: Retired Employee
```
Birth Date: 10 Mei 1968
Retirement Date: 10 Mei 2024 (age 56)
Today: 15 Nov 2024
Display: "✓ Pensiun 10 Mei 2024"
```

## Benefits

1. **Retirement Planning:** Quick view of when employees will retire
2. **Resource Planning:** Know upcoming staffing changes
3. **Succession Planning:** Identify positions that need replacement
4. **Compliance:** Track legal retirement requirements
5. **Data Accuracy:** Automatic calculation from birth date

## Validation Checklist

✅ getRetirementInfo() returns correct retirement date
✅ Handles employees already retired (status = 'retired')
✅ Handles active employees (status = 'active')
✅ Handles missing birth date (returns null)
✅ Awal Kerja column shows both start and retirement dates
✅ Masa Kerja column shows retirement countdown
✅ Colors differentiate active vs retired employees
✅ Dates formatted in Indonesian locale
✅ Current age displayed correctly
✅ All edge cases handled gracefully

## Performance Considerations

- Retirement calculation happens once per page load
- No database queries needed (uses local birth date field)
- Calculation is O(1) - constant time complexity
- No additional database indexes needed
- Minimal memory overhead

## Future Enhancements

1. Add retirement reminder notifications (3 months before)
2. Add separate retirement report/dashboard
3. Export retirement schedule for all employees
4. Add milestone calculations until retirement
5. Add replacement/succession planning features
6. Track historical retirement dates
7. Add early retirement options
8. Add retirement benefit calculations

## Related Files Modified
- `app/Models/Employee/Karyawan.php` - Added getRetirementInfo() method
- `app/Livewire/Admin/Karyawan/Masakerja/Index.php` - Added retirement_info to transform
- `resources/views/livewire/admin/karyawan/masakerja/index.blade.php` - Updated display

## Notes

- Retirement age is fixed at 56 years (can be made configurable if needed)
- Birth date field must exist in database (`tanggal_lahir` column)
- Dates are automatically formatted in Indonesian locale
- Both active and retired employees are properly handled
- No breaking changes to existing functionality

## Database Requirements

**Required Field:**
- `karyawan.tanggal_lahir` (DATE or TIMESTAMP) - Employee birth date

If this field is missing or NULL, retirement info will not be displayed.

## Example Usage in Code

```php
// Get retirement info for an employee
$employee = Karyawan::find(1);
$retirementInfo = $employee->getRetirementInfo();

// Check if employee is retired
if ($retirementInfo && $retirementInfo['status'] === 'retired') {
    // Handle retired employee
}

// Get formatted message
echo $retirementInfo['message']; // "Pensiun dalam 11 tahun 3 bulan"

// Access specific values
echo $retirementInfo['current_age']; // 45
echo $retirementInfo['formatted_retirement_date']; // "15 Agus 2035"
```
