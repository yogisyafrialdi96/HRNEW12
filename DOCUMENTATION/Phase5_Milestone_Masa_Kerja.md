# Phase 5: Milestone Masa Kerja Karyawan

## 📋 Overview

Implementasi fitur untuk menghitung dan menampilkan milestone masa kerja karyawan berdasarkan tanggal mulai kerja dari kontrak pertama. Sistem ini menampilkan 7 milestone (5, 10, 15, 20, 25, 30, 35 tahun) dengan status visual berbentuk badge berwarna.

## 🎯 Requirements

1. ✅ Hitung milestone masa kerja dari kontrak pertama karyawan
2. ✅ Milestone: 5, 10, 15, 20, 25, 30, 35 tahun
3. ✅ Tampilkan dengan warna/badge berbeda berdasarkan status
4. ✅ Read-only table (tidak ada action buttons)
5. ✅ Status indicator: Tercapai (Hijau), Segera (Merah), Mendatang (Biru)

## 🏗️ Architecture

### Data Flow

```
Karyawan (Model)
    ↓
    └─ calculateMilestones() → Menghitung tanggal milestone & status
    └─ getMilestoneBadgeConfig() → Ambil badge config per status
    ↓
Index.php (Livewire Component)
    ↓
    └─ render() → Load karyawan dengan contracts[]
    └─ Transform data → Attach milestones ke setiap karyawan
    ↓
Blade Template
    ↓
    └─ Display milestone date & badge per tahun
```

## 📝 Implementation

### 1. Model Karyawan (app/Models/Employee/Karyawan.php)

**Import Carbon:**
```php
use Carbon\Carbon;
```

**Method: calculateMilestones()**
```php
/**
 * Calculate work anniversary milestones for employee
 * Milestones: 5, 10, 15, 20, 25, 30, 35 years from first contract
 *
 * @return array|null Array of milestones with dates and status, or null if no contracts
 */
public function calculateMilestones()
{
    // Get first contract start date
    $startDate = null;
    if ($this->contracts && $this->contracts->count() > 0) {
        $startDate = Carbon::parse($this->contracts->first()->tglmulai_kontrak);
    } else {
        return null;
    }

    $now = Carbon::now();
    $milestones = [];

    // Calculate for each milestone year: 5, 10, 15, 20, 25, 30, 35
    foreach ([5, 10, 15, 20, 25, 30, 35] as $year) {
        $milestoneDate = $startDate->copy()->addYears($year);
        $daysUntil = $now->diffInDays($milestoneDate, false);

        // Determine status: achieved (past), upcoming-soon (<30 days), future
        if ($daysUntil < 0) {
            $status = 'achieved';
        } elseif ($daysUntil <= 30) {
            $status = 'upcoming-soon';
        } else {
            $status = 'future';
        }

        $milestones[$year] = [
            'year' => $year,
            'date' => $milestoneDate,
            'status' => $status,
            'daysUntil' => $daysUntil,
            'formatted_date' => $milestoneDate->format('d M Y'),
        ];
    }

    return $milestones;
}
```

**Method: getMilestoneBadgeConfig()**
```php
/**
 * Get milestone badge configuration based on status
 *
 * @param string $status The milestone status (achieved, upcoming-soon, future)
 * @return array Badge configuration with class and label
 */
public static function getMilestoneBadgeConfig($status)
{
    $badgeConfig = [
        'achieved' => [
            'class' => 'bg-green-100 text-green-800',
            'label' => 'Tercapai',
            'icon' => '✓'
        ],
        'upcoming-soon' => [
            'class' => 'bg-red-100 text-red-800',
            'label' => 'Segera',
            'icon' => '!'
        ],
        'future' => [
            'class' => 'bg-blue-100 text-blue-800',
            'label' => 'Mendatang',
            'icon' => '→'
        ]
    ];

    return $badgeConfig[$status] ?? ['class' => 'bg-gray-100 text-gray-800', 'label' => 'N/A', 'icon' => '?'];
}
```

### 2. Livewire Component (app/Livewire/Admin/Karyawan/Masakerja/Index.php)

**Query dengan relationships:**
```php
$query = Karyawan::with([
    'user',
    'activeJabatan' => function ($q) {
        $q->with(['jabatan', 'unit']);
    },
    'contracts' => function ($q) {
        $q->oldest('tglmulai_kontrak');
    }
]);
```

**Attach milestone data:**
```php
// Tambahkan data milestone untuk setiap karyawan
$karyawans->getCollection()->transform(function ($employee) {
    $employee->milestones = $employee->calculateMilestones();
    return $employee;
});
```

### 3. Blade Template (resources/views/livewire/admin/karyawan/masakerja/index.blade.php)

**Cell Structure (untuk setiap milestone):**
```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if($karyawan->milestones && isset($karyawan->milestones[5]))
        @php
            $milestone = $karyawan->milestones[5];
            $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
        @endphp
        <div class="flex flex-col gap-1">
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

**Milestone Columns:**
- 5th Anniversary
- 10th Anniversary
- 15th Anniversary
- 20th Anniversary
- 25th Anniversary
- 30th Anniversary

## 🎨 Badge Color Scheme

| Status | Badge Class | Label | Meaning |
|--------|-------------|-------|---------|
| **Tercapai** (Achieved) | `bg-green-100 text-green-800` | ✓ Tercapai | Milestone sudah lewat |
| **Segera** (Upcoming Soon) | `bg-red-100 text-red-800` | ! Segera | Dalam 30 hari ke depan |
| **Mendatang** (Future) | `bg-blue-100 text-blue-800` | → Mendatang | Lebih dari 30 hari |

## 📊 Data Structure

**Milestone Array per Employee:**
```php
[
    5 => [
        'year' => 5,
        'date' => Carbon instance,
        'status' => 'achieved|upcoming-soon|future',
        'daysUntil' => integer (negative if past),
        'formatted_date' => '01 Jan 2025'
    ],
    10 => [...],
    15 => [...],
    // ... dst
]
```

## ✅ Status Determination Logic

```
if (milestone_date < today) {
    status = 'achieved'  // Milestone sudah lewat
} elseif (milestone_date <= today + 30 days) {
    status = 'upcoming-soon'  // Akan datang dalam 30 hari
} else {
    status = 'future'  // Masih lama
}
```

## 🧪 Test Scenarios

### Scenario 1: Karyawan baru (0 tahun)
- **Tanggal Mulai:** 2025-01-01
- **Today:** 2025-01-15
- **Expected:**
  - 5th: 2030-01-01 (Future)
  - 10th: 2035-01-01 (Future)
  - etc.

### Scenario 2: Karyawan 5 tahun
- **Tanggal Mulai:** 2020-01-01
- **Today:** 2025-01-15
- **Expected:**
  - 5th: 2025-01-01 (Achieved - 14 hari lalu)
  - 10th: 2030-01-01 (Future)
  - etc.

### Scenario 3: Karyawan akan milestone segera
- **Tanggal Mulai:** 2015-02-15
- **Today:** 2025-01-15
- **Expected:**
  - 10th: 2025-02-15 (Upcoming Soon - 31 hari ke depan)
  - Badge: Merah (Segera)

### Scenario 4: Karyawan 30+ tahun
- **Tanggal Mulai:** 1990-06-01
- **Today:** 2025-01-15
- **Expected:**
  - 5th: 1995-06-01 (Achieved)
  - 10th: 2000-06-01 (Achieved)
  - 15th: 2005-06-01 (Achieved)
  - 20th: 2010-06-01 (Achieved)
  - 25th: 2015-06-01 (Achieved)
  - 30th: 2020-06-01 (Achieved)

## 🔧 Usage in View

**Access milestone data:**
```blade
{{-- Check if milestone exists --}}
@if($karyawan->milestones && isset($karyawan->milestones[5]))
    {{-- Get formatted date --}}
    {{ $karyawan->milestones[5]['formatted_date'] }}
    
    {{-- Get status --}}
    {{ $karyawan->milestones[5]['status'] }}
    
    {{-- Get days until (negative if past) --}}
    {{ $karyawan->milestones[5]['daysUntil'] }}
@endif
```

## 📁 Files Modified

1. **app/Models/Employee/Karyawan.php**
   - Added: `use Carbon\Carbon;`
   - Added: `calculateMilestones()` method
   - Added: `getMilestoneBadgeConfig()` static method

2. **app/Livewire/Admin/Karyawan/Masakerja/Index.php**
   - Modified: `render()` method
   - Added: Transform logic untuk attach milestones

3. **resources/views/livewire/admin/karyawan/masakerja/index.blade.php**
   - Modified: 6 milestone cell templates (5th-30th)
   - Added: Badge display dengan conditional rendering

## 🚀 Features

✅ **Milestone Calculation**
- Otomatis hitung dari kontrak pertama
- Support 7 milestone: 5, 10, 15, 20, 25, 30, 35 tahun

✅ **Status Tracking**
- Tercapai (Hijau) - Milestone sudah lewat
- Segera (Merah) - Akan datang dalam 30 hari
- Mendatang (Biru) - Lebih dari 30 hari

✅ **Visual Indicators**
- Badge dengan warna berbeda per status
- Formatted date display (d M Y)
- Responsive table layout

✅ **Edge Cases**
- Handle employee tanpa kontrak (null)
- Handle undefined milestone
- Fallback text jika data tidak ada

## 📋 Related Tables

- `karyawan` - Master karyawan
- `karyawan_kontrak` - Contract history dengan `tglmulai_kontrak`
- `karyawan_jabatan` - Active position

## 🔍 Validation

- ✅ PHP Syntax: No errors
- ✅ Carbon date handling: Correct
- ✅ Badge config static method: Accessible
- ✅ Null safety: All handled with conditionals

## 📞 Support

**If milestone doesn't show:**
1. Check if `contracts` relationship loaded
2. Verify `tglmulai_kontrak` has value
3. Check if `milestones` attribute attached in render()
4. Verify Blade template has correct milestone index [5, 10, 15, etc]

**Common Issues:**

| Issue | Solution |
|-------|----------|
| Milestone tidak muncul | Verify contracts loaded & transform called |
| Badge tidak tampil | Check getMilestoneBadgeConfig($status) |
| Date format salah | Verify formatted_date format 'd M Y' |
| Status always future | Check daysUntil calculation & comparison |

