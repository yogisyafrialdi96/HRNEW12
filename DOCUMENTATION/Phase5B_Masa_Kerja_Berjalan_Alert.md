# Phase 5B: Masa Kerja Berjalan + Milestone Alert Badge

## 📋 Overview

Penambahan fitur untuk menampilkan masa kerja berjalan karyawan dan menambahkan visual alert (animated badge) untuk milestone yang akan datang dalam 30 hari mendatang.

## 🎯 Requirements

1. ✅ Tampilkan masa kerja berjalan (years + months) dalam 1 kolom baru
2. ✅ Tambahkan alert badge pada masa kerja berjalan jika ada milestone segera datang
3. ✅ Animated alert indicator pada setiap milestone cell yang akan datang dalam 30 hari
4. ✅ Format: "X Tahun Y Bulan" + total hari
5. ✅ Visual indicator: Animated red pulsing dot untuk upcoming-soon milestones

## 🏗️ Architecture

### New Methods di Model Karyawan

```
Karyawan Model
    ├── getCurrentWorkDuration()  → Calculate masa kerja berjalan (years, months, days)
    ├── getUpcomingSoonMilestone() → Get first milestone dalam 30 hari
    └── calculateMilestones()  → Existing (unchanged)
```

### Data Flow

```
Karyawan
    ↓
getCurrentWorkDuration()
    └── return: { years, months, days, total_days, formatted, short }
    ↓
getUpcomingSoonMilestone()
    └── return: year (5, 10, 15, etc) or null
    ↓
Index.php render() → transform & attach both
    ↓
Blade Template
    ├── Display current_duration in new column
    ├── Show alert badge jika upcoming_milestone exists
    └── Show animated red dot on milestone cells
```

## 📝 Implementation Details

### 1. New Model Methods (app/Models/Employee/Karyawan.php)

#### Method: getCurrentWorkDuration()

```php
/**
 * Calculate current work duration (masa kerja berjalan)
 * Returns years and months from first contract start date to now
 *
 * @return array|null Array with years and months, or null if no contracts
 */
public function getCurrentWorkDuration()
{
    if (!$this->contracts || $this->contracts->count() === 0) {
        return null;
    }

    $startDate = Carbon::parse($this->contracts->first()->tglmulai_kontrak);
    $now = Carbon::now();

    $years = $now->diffInYears($startDate);
    $months = $now->diffInMonths($startDate) % 12;
    $days = $now->diffInDays($startDate->copy()->addYears($years)->addMonths($months));

    return [
        'years' => $years,
        'months' => $months,
        'days' => $days,
        'total_days' => $now->diffInDays($startDate),
        'formatted' => sprintf('%d Tahun %d Bulan', $years, $months),
        'short' => sprintf('%d.%d Tahun', $years, $months),
    ];
}
```

**Returns:**
```php
[
    'years' => 5,
    'months' => 3,
    'days' => 15,
    'total_days' => 1940,
    'formatted' => '5 Tahun 3 Bulan',
    'short' => '5.3 Tahun'
]
```

#### Method: getUpcomingSoonMilestone()

```php
/**
 * Check if any milestone is upcoming soon (within 30 days)
 * Returns the milestone year that is coming soon, or null
 *
 * @return int|null The milestone year that is upcoming soon, or null
 */
public function getUpcomingSoonMilestone()
{
    $milestones = $this->calculateMilestones();
    
    if (!$milestones) {
        return null;
    }

    foreach ($milestones as $year => $milestone) {
        if ($milestone['status'] === 'upcoming-soon') {
            return $year;
        }
    }

    return null;
}
```

**Returns:**
```php
10  // Jika milestone 10 tahun dalam 30 hari ke depan
// atau null jika tidak ada upcoming-soon milestone
```

### 2. Updated Livewire Component (Index.php)

**Transform data di render() method:**
```php
// Tambahkan data milestone untuk setiap karyawan
$karyawans->getCollection()->transform(function ($employee) {
    $employee->milestones = $employee->calculateMilestones();
    $employee->current_duration = $employee->getCurrentWorkDuration();
    $employee->upcoming_milestone = $employee->getUpcomingSoonMilestone();
    return $employee;
});
```

### 3. Updated Blade Template

#### Kolom Baru: "Masa Kerja Berjalan"

Header:
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    <div class="flex items-center gap-2">
        <span>Masa Kerja Berjalan</span>
    </div>
</th>
```

Data Cell:
```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if($karyawan->current_duration)
        <div class="flex flex-col gap-1">
            <span class="text-sm font-semibold text-gray-900">{{ $karyawan->current_duration['formatted'] }}</span>
            <span class="text-xs text-gray-500">({{ $karyawan->current_duration['total_days'] }} hari)</span>
            {{-- Badge jika ada milestone yang akan datang dalam 30 hari --}}
            @if($karyawan->upcoming_milestone)
                <span class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 w-fit mt-1">
                    ⚠️ Milestone {{ $karyawan->upcoming_milestone }} Th
                </span>
            @endif
        </div>
    @else
        <span class="text-gray-400 text-sm">-</span>
    @endif
</td>
```

#### Alert Badge pada Milestone Cells

Setiap milestone cell (5th, 10th, 15th, 20th, 25th, 30th) sekarang memiliki:

```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if($karyawan->milestones && isset($karyawan->milestones[5]))
        @php
            $milestone = $karyawan->milestones[5];
            $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
        @endphp
        <div class="flex flex-col gap-1 relative">
            {{-- Badge alert jika milestone segera datang --}}
            @if($milestone['status'] === 'upcoming-soon')
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                </span>
            @endif
            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }} w-fit">
                {{ $badge['label'] }}
            </span>
        </div>
    @else
        <span class="text-gray-400 text-sm">-</span>
    @endif
</td>
```

**Animated Badge Detail:**
- Position: Absolute top-right corner
- Animation: `animate-ping` (pulsing red)
- Size: 4x4 (h-4 w-4)
- Color: Red (bg-red-500)
- Effect: Outer ring animates, inner dot fixed

## 📊 Data Structure

### getCurrentWorkDuration() Result

```php
[
    'years' => 5,                              // Integer tahun
    'months' => 3,                             // Integer bulan (0-11)
    'days' => 15,                              // Integer sisa hari
    'total_days' => 1940,                      // Total hari dari awal
    'formatted' => '5 Tahun 3 Bulan',         // Full format untuk display
    'short' => '5.3 Tahun'                     // Short format (optional)
]
```

### getUpcomingSoonMilestone() Result

```php
// Jika ada milestone 10 tahun dalam 30 hari
10

// Atau null jika tidak ada upcoming-soon milestone
null
```

## 🧪 Test Scenarios

### Scenario 1: Karyawan baru
**Tanggal Mulai:** 2025-10-01  
**Hari ini:** 2025-11-12  
**Expected:**
- Current Duration: 1 Tahun 0 Bulan (42 hari)
- Upcoming Milestone: null (semua future)
- Milestone badges: Tidak ada yang merah

### Scenario 2: Karyawan akan milestone segera
**Tanggal Mulai:** 2015-10-01  
**Hari ini:** 2025-11-12  
**Expected:**
- Current Duration: 10 Tahun 1 Bulan (3677 hari)
- Upcoming Milestone: null (10 tahun sudah lewat)
- Semua milestone 10 tahun ke bawah: Tercapai (Hijau)

### Scenario 3: Karyawan mendekati 10 tahun
**Tanggal Mulai:** 2015-12-15  
**Hari ini:** 2025-11-12  
**Expected:**
- Current Duration: 9 Tahun 10 Bulan (3613 hari)
- Upcoming Milestone: 10 (akan datang 33 hari lagi = 2025-12-15)
- Badge Alert: "⚠️ Milestone 10 Th" pada kolom Masa Kerja
- Milestone 10th cell: Red pulsing animated dot

### Scenario 4: Karyawan 30+ tahun
**Tanggal Mulai:** 1990-06-01  
**Hari ini:** 2025-11-12  
**Expected:**
- Current Duration: 35 Tahun 5 Bulan (12954 hari)
- Upcoming Milestone: null
- Semua milestone: Tercapai (Hijau)

## 🎨 Visual Elements

### Masa Kerja Berjalan Cell
```
┌─────────────────────────────────────┐
│ 5 Tahun 3 Bulan                    │
│ (1940 hari)                         │
│ ⚠️ Milestone 10 Th                 │ ← Jika ada upcoming
└─────────────────────────────────────┘
```

### Milestone Cell dengan Alert
```
┌──────────────────────┐
│ 🔴 (animated)       │ ← Jika upcoming-soon
│ 15 Jan 2030         │
│ [Mendatang]         │
└──────────────────────┘
```

Red pulsing dot animation:
- Outer: Continuously ping/pulse (opacity fade)
- Inner: Solid red dot

## 📁 Files Modified

1. **app/Models/Employee/Karyawan.php**
   - Added: `getCurrentWorkDuration()` method
   - Added: `getUpcomingSoonMilestone()` method

2. **app/Livewire/Admin/Karyawan/Masakerja/Index.php**
   - Modified: `render()` transform logic
   - Added: `$employee->current_duration` attachment
   - Added: `$employee->upcoming_milestone` attachment

3. **resources/views/livewire/admin/karyawan/masakerja/index.blade.php**
   - Added: "Masa Kerja Berjalan" header column
   - Added: New data cell dengan duration display
   - Modified: All 6 milestone cells (5, 10, 15, 20, 25, 30)
   - Added: Animated alert badge (red pulsing dot)

## ✅ Features

✅ **Masa Kerja Berjalan Display**
- Format: "X Tahun Y Bulan"
- Show total days
- Real-time calculation from first contract

✅ **Alert Badge pada Kolom Masa Kerja**
- Display: "⚠️ Milestone XX Th"
- Warna: Merah (bg-red-100 text-red-800)
- Tampil hanya jika ada milestone dalam 30 hari

✅ **Animated Alert Indicator pada Milestone Cell**
- Visual: Pulsing red dot (top-right corner)
- Animation: `animate-ping` (Tailwind)
- Triggered: Ketika milestone status = "upcoming-soon"
- Effect: Eye-catching untuk milestone segera datang

✅ **Responsive Design**
- Kolom tidak terlalu lebar
- Badge auto-fit dengan konten
- Alert dot positioned correctly

✅ **Null Safety**
- Handle employee tanpa kontrak
- Handle undefined upcoming_milestone
- Fallback text "-" jika tidak ada data

## 🔍 Usage in View

**Access current duration:**
```blade
{{-- Display duration --}}
{{ $karyawan->current_duration['formatted'] }}

{{-- Display total days --}}
{{ $karyawan->current_duration['total_days'] }}

{{-- Display years only --}}
{{ $karyawan->current_duration['years'] }}
```

**Check upcoming milestone:**
```blade
@if($karyawan->upcoming_milestone)
    Upcoming milestone: {{ $karyawan->upcoming_milestone }} tahun
@endif
```

## 📋 Column Order

1. No
2. Nama (dengan foto, jabatan, unit)
3. NIP
4. Awal Kerja
5. **Masa Kerja Berjalan** ← NEW
6. 5th Anniversary
7. 10th Anniversary
8. 15th Anniversary
9. 20th Anniversary
10. 25th Anniversary
11. 30th Anniversary

## 🚀 Performance Considerations

- Methods calculated on-the-fly (no caching)
- Transform only on paginated collection
- No additional database queries
- Calculations using Carbon (built-in)

## 🔧 Customization

**Change upcoming-soon threshold (default 30 days):**
- Edit in `calculateMilestones()` method
- Line: `elseif ($daysUntil <= 30)`

**Change alert badge emoji:**
- Edit in template: `⚠️` → `🔔`, `⏰`, etc

**Change animation speed:**
- Tailwind `animate-ping` is CSS-based
- Default duration: 1 second

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Duration tidak muncul | Check if contracts loaded & transform called |
| Alert badge tidak show | Verify upcoming_milestone is attached in render() |
| Animated dot tidak bergerak | Check Tailwind animation support |
| Wrong calculation | Verify tglmulai_kontrak is valid date |

## 📞 Support

**For debugging:**
```php
// In terminal or log
dd($karyawan->current_duration);
dd($karyawan->upcoming_milestone);
dd($karyawan->milestones);
```

