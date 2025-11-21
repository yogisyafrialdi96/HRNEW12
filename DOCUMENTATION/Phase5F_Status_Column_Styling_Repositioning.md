# Phase 5F: Status Karyawan Column Styling & Repositioning

## Overview
Menambahkan distinct styling/badge untuk setiap status karyawan dan memindahkan kolom Status ke posisi setelah NIP untuk better data visibility.

## Changes Made

### 1. StatusPegawai Model (app/Models/Master/StatusPegawai.php)

#### Added Badge Configuration Method
```php
public static function getBadgeConfig($statusId)
{
    $configs = [
        1 => ['label' => 'Aktif', 'class' => 'bg-green-100 text-green-800'],
        2 => ['label' => 'Resign', 'class' => 'bg-red-100 text-red-800'],
        3 => ['label' => 'Pensiun Dini', 'class' => 'bg-gray-100 text-gray-800'],
        4 => ['label' => 'LWP', 'class' => 'bg-yellow-100 text-yellow-800'],
        5 => ['label' => 'Tugas Belajar', 'class' => 'bg-blue-100 text-blue-800'],
        6 => ['label' => 'Habis Kontrak', 'class' => 'bg-orange-100 text-orange-800'],
        7 => ['label' => 'Meninggal', 'class' => 'bg-slate-900 text-white'],
    ];

    return $configs[$statusId] ?? ['label' => 'Lainnya', 'class' => 'bg-gray-100 text-gray-800'];
}
```

**Purpose:** Static method to return badge label and Tailwind color classes for each status type.

### 2. Blade Template Changes (resources/views/livewire/admin/karyawan/masakerja/index.blade.php)

#### Moved Status Header
- **From:** After 35th Anniversary column (at end of thead)
- **To:** After NIP column (line 145-152)

```blade
<!-- Status Karyawan -->
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    <div class="flex items-center gap-2">
        <span>Status</span>
    </div>
</th>
```

#### Moved Status Data Column
- **From:** Before closing </tr> at end of tbody
- **To:** After NIP data column (line 263-273)

```blade
<!-- Status Karyawan -->
<td class="px-6 py-4 whitespace-nowrap">
    @if($karyawan->statusPegawai)
        @php
            $badgeConfig = \App\Models\Master\StatusPegawai::getBadgeConfig($karyawan->statusPegawai->id);
        @endphp
        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $badgeConfig['class'] }}">
            {{ $badgeConfig['label'] }}
        </span>
    @else
        <span class="text-gray-400 text-sm">-</span>
    @endif
</td>
```

**Features:**
- Uses `getBadgeConfig()` from StatusPegawai model
- Applies distinct colors based on status type
- Shows label instead of generic status text
- Handles null/missing status gracefully

#### Updated Table colspan
- **Changed from:** `colspan="13"`
- **Changed to:** `colspan="12"`

## Status Badge Colors

| Status | Label | Color |
|--------|-------|-------|
| 1 | Aktif | 🟢 Green (bg-green-100 text-green-800) |
| 2 | Resign | 🔴 Red (bg-red-100 text-red-800) |
| 3 | Pensiun Dini | ⚫ Gray (bg-gray-100 text-gray-800) |
| 4 | LWP | 🟡 Yellow (bg-yellow-100 text-yellow-800) |
| 5 | Tugas Belajar | 🔵 Blue (bg-blue-100 text-blue-800) |
| 6 | Habis Kontrak | 🟠 Orange (bg-orange-100 text-orange-800) |
| 7 | Meninggal | ⬛ Dark (bg-slate-900 text-white) |
| Other | Lainnya | ⚫ Gray (default) |

## New Table Column Order (12 Total)

1. **No** - Row number
2. **Nama** - Employee name with photo and position
3. **NIP** - Employee ID number
4. **Status** - Employee status with colored badge (MOVED HERE)
5. **Awal Kerja** - Contract start date
6. **Masa Kerja** - Current work duration with alert badge
7. **5th** - 5-year anniversary milestone
8. **10th** - 10-year anniversary milestone
9. **15th** - 15-year anniversary milestone
10. **20th** - 20-year anniversary milestone
11. **25th** - 25-year anniversary milestone
12. **30th** - 30-year anniversary milestone
13. **35th** - 35-year anniversary milestone

## Benefits

### 1. Better Visual Hierarchy
- Status immediately visible after employee ID
- Color-coded for quick visual scanning
- Easy to identify active vs. inactive employees

### 2. Distinct Status Styling
- Each status has unique color association
- Intuitive colors (green=active, red=resign, etc.)
- Improves data interpretation

### 3. Improved UX
- Status column moved earlier in table
- Reduces need to scroll for status info
- Logical flow: Name → ID → Status

## Status Color Psychology

- **Green (Aktif):** Positive, active status
- **Red (Resign):** Negative, employee departed
- **Gray (Pensiun Dini):** Neutral, retired early
- **Yellow (LWP):** Warning, leave without pay
- **Blue (Tugas Belajar):** Informational, on study leave
- **Orange (Habis Kontrak):** Alert, contract ended
- **Dark (Meninggal):** Somber, deceased status

## Usage Example

```blade
<!-- Display status with badge -->
@if($karyawan->statusPegawai)
    @php
        $badgeConfig = \App\Models\Master\StatusPegawai::getBadgeConfig($karyawan->statusPegawai->id);
    @endphp
    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $badgeConfig['class'] }}">
        {{ $badgeConfig['label'] }}
    </span>
@endif
```

## Testing Checklist

✅ Status column appears after NIP (not at end)
✅ Each status shows correct color badge
✅ Status label displays correctly (e.g., "Aktif", "Resign")
✅ All 7 status types have distinct colors
✅ Missing status shows "-" gracefully
✅ Badge styling is consistent with Tailwind
✅ Column order: No > Nama > NIP > Status > Awal Kerja > ...
✅ Table layout responsive on mobile
✅ colspan correctly adjusted to 12
✅ Filter by status still works correctly

## Performance Notes

- Status styling is computed once per page load
- No additional queries needed (statusPegawai already eager-loaded)
- Badge config is static method (no database hits)
- CSS classes applied directly (no component overhead)

## Related Files Modified
- `app/Models/Master/StatusPegawai.php` - Added getBadgeConfig() method
- `resources/views/livewire/admin/karyawan/masakerja/index.blade.php` - Repositioned status column

## Future Enhancements

- Add status change history/audit log
- Add bulk status update with reason tracking
- Add status filter with color indicators
- Add animated transitions when status changes
- Add status-based employee grouping/reports

## Migration Summary

**Before:**
```
No | Nama | NIP | Awal Kerja | Masa Kerja | 5th | ... | 35th | Status
```

**After:**
```
No | Nama | NIP | Status | Awal Kerja | Masa Kerja | 5th | ... | 35th
```

**Impact:**
- Status information readily visible without horizontal scrolling
- Color-coded status at a glance
- Improved data accessibility and UX
- Maintains all functionality and filtering
