# Phase 5E: Status Karyawan Filter + 35th Milestone Column

## Overview
Implementasi filter dinamis berdasarkan status karyawan dan menambahkan kolom 35th Anniversary milestone pada halaman Masakerja.

## Changes Made

### 1. Karyawan Model (app/Models/Employee/Karyawan.php)

#### Added Import
```php
use App\Models\Master\StatusPegawai;
```

#### Added Relationship
```php
public function statusPegawai(): BelongsTo
{
    return $this->belongsTo(StatusPegawai::class, 'statuskaryawan_id');
}
```

**Purpose:** Creates relationship between Karyawan and StatusPegawai model for accessing status data.

### 2. Index Component (app/Livewire/Admin/Karyawan/Masakerja/Index.php)

#### Added Imports
```php
use App\Models\Master\StatusPegawai;
```

#### Added Filter Property
```php
public $statusFilter = '';
```

#### Added Updater Method
```php
public function updatingStatusFilter()
{
    $this->resetPage();
}
```

#### Added Method to Get Available Statuses
```php
public function getAvailableStatusesProperty()
{
    return StatusPegawai::orderBy('nama_status')->get();
}
```

**Purpose:** Returns all available status pegawai options for the filter dropdown, ordered alphabetically.

#### Updated Query with statusPegawai Relationship
```php
$query = Karyawan::with([
    'user',
    'statusPegawai',  // Added
    'activeJabatan' => function ($q) {
        $q->with(['jabatan', 'unit']);
    },
    'contracts' => function ($q) {
        $q->oldest('tglmulai_kontrak');
    }
]);
```

#### Added Status Filter Logic
```php
// Filter by status karyawan (dari StatusPegawai)
$query->when($this->statusFilter !== '', function ($q) {
    $q->where('statuskaryawan_id', $this->statusFilter);
});
```

**Logic:** Filters employees by their status pegawai ID. Empty value shows all statuses.

### 3. Blade Template (resources/views/livewire/admin/karyawan/masakerja/index.blade.php)

#### Updated Filter Section Grid
Changed from `sm:grid-cols-4` to `sm:grid-cols-3` and `lg:col-span-3` to `lg:col-span-4` to accommodate 3 filters.

#### Added Status Filter Dropdown
```blade
<!-- Status Filter -->
<select wire:model.live="statusFilter"
    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
    <option value="">All Status</option>
    @foreach ($this->availableStatuses as $status)
        <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
    @endforeach
</select>
```

**Features:**
- Dynamically populated from availableStatuses property
- Shows all StatusPegawai records
- Real-time filtering with wire:model.live

#### Added 35th Anniversary Column Header
```blade
<!-- 35th Anniversary -->
<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
    <div class="flex items-center justify-center gap-2">
        <span>35th</span>
    </div>
</th>
```

#### Added Status Karyawan Column Header
```blade
<!-- Status Karyawan -->
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    <div class="flex items-center gap-2">
        <span>Status</span>
    </div>
</th>
```

#### Added 35th Anniversary Data Column
```blade
<!-- 35th Anniversary -->
<td class="px-6 py-4 whitespace-nowrap text-center">
    @if($karyawan->milestones && isset($karyawan->milestones[35]))
        @php
            $milestone = $karyawan->milestones[35];
            $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
        @endphp
        <div class="flex flex-col gap-1 relative items-center">
            {{-- Badge alert jika milestone segera datang --}}
            @if($milestone['status'] === 'upcoming-soon')
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                </span>
            @endif
            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                {{ $badge['label'] }}
            </span>
        </div>
    @else
        <span class="text-gray-400 text-sm">-</span>
    @endif
</td>
```

**Features:**
- Shows 35-year anniversary milestone date
- Displays status badge (achieved/upcoming-soon/future)
- Shows animated alert dot if milestone is within 30 days

#### Added Status Karyawan Data Column
```blade
<!-- Status Karyawan -->
<td class="px-6 py-4 whitespace-nowrap">
    @if($karyawan->statusPegawai)
        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
            {{ $karyawan->statusPegawai->nama_status }}
        </span>
    @else
        <span class="text-gray-400 text-sm">-</span>
    @endif
</td>
```

**Features:**
- Displays the employee's current status
- Shows in blue badge style
- Handles null status gracefully

#### Updated colspan
Changed from `colspan="11"` to `colspan="13"` for the empty state message to account for 2 new columns.

## Table Structure Summary

### Column Layout (13 Total Columns)
1. **No** - Row number
2. **Nama** - Employee name with photo and position
3. **NIP** - Employee ID number
4. **Awal Kerja** - Contract start date
5. **Masa Kerja** - Current work duration with alert badge
6. **5th** - 5-year anniversary milestone
7. **10th** - 10-year anniversary milestone
8. **15th** - 15-year anniversary milestone
9. **20th** - 20-year anniversary milestone
10. **25th** - 25-year anniversary milestone
11. **30th** - 30-year anniversary milestone
12. **35th** - 35-year anniversary milestone (NEW)
13. **Status** - Employee status (NEW)

## Filter System

### Available Filters
1. **Unit Filter** - Filters by employee's active unit (excludes Yayasan department)
2. **Milestone Filter** - Shows employees with specific work anniversary years (5, 10, 15, 20, 25, 30, 35)
3. **Status Filter** - Shows employees with specific status (Aktif, Resign, Pensiun Dini, etc.)
4. **Search** - Search by name, NIP, or contract start date

### Filter Behavior
- All filters are optional (empty value shows all)
- Filters can be combined (e.g., filter by unit AND status)
- Filters trigger pagination reset to page 1
- Status filter is dynamically populated from database

## Data Relationships

```
Karyawan
  ├─ statusPegawai (BelongsTo StatusPegawai)
  ├─ activeJabatan (HasOne KaryawanJabatan)
  │   ├─ unit (BelongsTo Units)
  │   └─ jabatan (BelongsTo Jabatans)
  └─ contracts (HasMany KaryawanKontrak)
      └─ tglmulai_kontrak (for milestone calculation)
```

## Testing Checklist

✅ Status filter dropdown shows all available statuses
✅ Status filter works correctly (filters by selected status)
✅ 35th anniversary column displays correctly
✅ 35th milestone shows alert badge when upcoming-soon
✅ Status column displays employee's current status
✅ Status filter can be combined with other filters
✅ All filters reset pagination properly
✅ Search still works with all new filters
✅ Responsive on mobile devices
✅ Empty state displays correctly

## Key Features

### Dynamic Status Dropdown
- Automatically loaded from StatusPegawai table
- Always up-to-date with database
- Ordered alphabetically by status name

### 35th Anniversary Milestone
- Last milestone in the series
- Uses same badge styling as other milestones
- Shows alert dot if within 30 days
- Displays formatted date and status

### Status Column
- Shows current status in colored badge
- Uses blue color for visual consistency
- Handles null/missing status gracefully
- Non-sortable (attribute only)

### Filter Persistence
- Filters are reactive with Livewire
- Real-time filtering with wire:model.live
- URL state preserved for sortable fields
- Search is separate and works with all filters

## Related Files Modified
- `app/Models/Employee/Karyawan.php` - Added statusPegawai relationship
- `app/Livewire/Admin/Karyawan/Masakerja/Index.php` - Added status filter logic
- `resources/views/livewire/admin/karyawan/masakerja/index.blade.php` - Updated UI

## Performance Notes
- statusPegawai is eager-loaded to avoid N+1 queries
- Status filter uses indexed foreign key (statuskaryawan_id)
- Filter queries are optimized with proper relationship usage

## Future Enhancements
- Add export functionality (with selected filters applied)
- Add bulk status update feature
- Add status change history tracking
- Add more milestone years if needed (40, 45 years)
- Add anniversary reminder notifications

## Notes

The new status column makes it easy to:
1. Identify which employees are still active
2. See employees at different career stages (resign, pension, etc.)
3. Understand workforce composition
4. Plan for retirement/turnover

The 35th anniversary milestone completion closes the milestone tracking range, making the system comprehensive for long-tenured employees.
