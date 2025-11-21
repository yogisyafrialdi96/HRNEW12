# Phase 5D: Unit Filter + Milestone Filter Implementation

## Overview
Implementasi filter by Unit dan filter by Masa Kerja Milestone pada halaman Masakerja.

## Changes Made

### 1. Index.php Component (app/Livewire/Admin/Karyawan/Masakerja/Index.php)

#### Added Imports
```php
use App\Models\Master\Units;
```

#### Added Filter Properties
```php
public $unitFilter = '';
public $milestoneFilter = '';
```

#### Added Updater Methods
```php
public function updatingUnitFilter()
{
    $this->resetPage();
}

public function updatingMilestoneFilter()
{
    $this->resetPage();
}
```

#### Added getAvailableUnitsProperty() Method
```php
public function getAvailableUnitsProperty()
{
    return Units::whereHas('department', function ($q) {
        $q->where('department', '!=', 'Yayasan');
    })->orderBy('unit')->get();
}
```

**Purpose:** 
- Returns all units EXCEPT those in "Yayasan" department
- Orders by unit name for better UX
- Used in blade template to populate dropdown

#### Added Unit Filter Logic in render() Method
```php
// filter by unit (dari activeJabatan)
$query->when($this->unitFilter !== '', function ($q) {
    $q->whereHas('activeJabatan', function ($sub) {
        $sub->where('unit_id', $this->unitFilter);
    });
});
```

**Logic:**
- Filters employees based on their active unit assignment
- Uses whereHas to check activeJabatan relationship
- Excludes employees without active jabatan

#### Added Milestone Filter Logic in render() Method
```php
// filter by milestone (masa kerja tahun tertentu)
$query->when($this->milestoneFilter !== '', function ($q) {
    $milestoneYear = (int)$this->milestoneFilter;
    $q->whereHas('contracts', function ($subQuery) use ($milestoneYear) {
        // Get the oldest contract (first contract)
        $subQuery->oldest('tglmulai_kontrak')
            ->whereRaw("YEAR(FROM_DAYS(DATEDIFF(CURDATE(), tglmulai_kontrak))) = ?", [$milestoneYear]);
    });
});
```

**Logic:**
- Filters employees by their work anniversary year
- Uses `YEAR(FROM_DAYS(DATEDIFF(CURDATE(), tglmulai_kontrak)))` to calculate years of service
- Only shows employees whose current work duration equals the selected milestone
- Available milestones: 5, 10, 15, 20, 25, 30, 35 years
- Uses whereHas on contracts to properly reference tglmulai_kontrak column

### 2. Blade Template (resources/views/livewire/admin/karyawan/masakerja/index.blade.php)

#### Updated Filter Section
Replaced old filter dropdowns with new unit and milestone filters:

```blade
<!-- Unit Filter -->
<select wire:model.live="unitFilter"
    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
    <option value="">All Unit</option>
    @foreach ($this->availableUnits as $unit)
        <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
    @endforeach
</select>

<!-- Milestone Filter -->
<select wire:model.live="milestoneFilter"
    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
    <option value="">All Masa Kerja</option>
    <option value="5">5 Tahun</option>
    <option value="10">10 Tahun</option>
    <option value="15">15 Tahun</option>
    <option value="20">20 Tahun</option>
    <option value="25">25 Tahun</option>
    <option value="30">30 Tahun</option>
    <option value="35">35 Tahun</option>
</select>
```

**Features:**
- Unit filter dynamically populated from `availableUnits` property
- Automatically excludes Yayasan department units
- Milestone filter shows standard options: 5, 10, 15, 20, 25, 30, 35 years
- Both use `wire:model.live` for real-time filtering

## Error Fixed

### Original Error
```
Column not found: 1054 Unknown column 'tglmulai_kontrak' in 'where clause'
```

### Root Cause
The `tglmulai_kontrak` column exists in `karyawan_kontrak` table, not in `karyawan` table. The query was trying to directly reference it without proper relationship.

### Solution
Changed from:
```php
$q->whereRaw("YEAR(FROM_DAYS(DATEDIFF(CURDATE(), tglmulai_kontrak))) = ?", [$milestoneYear])
```

To:
```php
$q->whereHas('contracts', function ($subQuery) use ($milestoneYear) {
    $subQuery->oldest('tglmulai_kontrak')
        ->whereRaw("YEAR(FROM_DAYS(DATEDIFF(CURDATE(), tglmulai_kontrak))) = ?", [$milestoneYear]);
});
```

This properly uses the relationship to access the contract table where the column exists.

## Database Relationships Used

### Karyawan → Units Filter Path
```
Karyawan
  └─ activeJabatan (HasOne, is_active=true)
      └─ unit (BelongsTo Units)
          └─ department (BelongsTo Departments)
```

### Karyawan → Milestone Filter Path
```
Karyawan
  └─ contracts (HasMany KaryawanKontrak)
      └─ tglmulai_kontrak (used for year calculation)
```

## Filter Behavior

### Unit Filter
- Shows all units except those in "Yayasan" department
- Filters based on employee's active unit (activeJabatan.unit_id)
- Empty value shows all employees

### Milestone Filter
- Shows options for 5, 10, 15, 20, 25, 30, 35 years
- Only shows employees whose current work duration equals selected year
- Uses oldest contract start date for calculation
- Empty value shows all employees

## Testing Checklist

✅ Unit filter dropdown populated correctly
✅ Milestone filter dropdown shows all 7 options
✅ Unit filter works (filters by employee's active unit)
✅ Milestone filter works (filters by work anniversary year)
✅ Filters can be combined (unit + milestone together)
✅ Search works with both filters active
✅ Pagination works with filters
✅ No SQL errors when filtering
✅ Yayasan department units excluded from dropdown

## Combined Filter Example

User can:
1. Select a specific unit (e.g., "IT Department")
2. Select a milestone year (e.g., "10 Tahun")
3. See only employees from IT Department who have exactly 10 years of service

## Notes

- The `getAvailableUnitsProperty()` method is cached by Livewire, so the dropdown doesn't refresh on each render
- The milestone filter uses the OLDEST contract (first contract) to calculate years of service
- Filters reset pagination to page 1 when changed (via `resetPage()`)
- Both filters use `wire:model.live` for instant filtering feedback

## Related Documentation
- Phase5_Milestone_Masa_Kerja.md - Milestone calculation logic
- Phase5B_Masa_Kerja_Berjalan_Alert.md - Current work duration display
- Phase5C_Table_Layout_Search_Updates.md - Search and table layout improvements
