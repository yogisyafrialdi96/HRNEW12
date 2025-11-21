# Feature: Kontrak TETAP (Permanent) - Disable Tanggal Selesai

## 📋 Requirements
1. **Jika jenis kontrak = "TETAP"** → Tanggal selesai harus disable
2. **Sisa kontrak untuk TETAP** → Display "Tidak terbatas" (unlimited)
3. **Auto status sync** → Jika tanggal selesai sudah habis → status jadi "selesai"

## ✅ Implementation

### 1. Backend Changes - Component Logic

**File:** `app/Livewire/Admin/Karyawan/Kontrak/Index.php`

#### Method: `getSelectedKontrakType()`
Mengambil nama kontrak dari selected kontrak_id
```php
public function getSelectedKontrakType()
{
    if (!$this->kontrak_id) {
        return null;
    }
    
    $kontrak = Kontrak::find($this->kontrak_id);
    return $kontrak ? $kontrak->nama_kontrak : null;
}
```

#### Method: `isKontrakTetap()`
Check apakah kontrak yang dipilih adalah "TETAP"
```php
public function isKontrakTetap()
{
    $tipe = $this->getSelectedKontrakType();
    return $tipe && strtoupper(trim($tipe)) === 'TETAP';
}
```

#### Method: `updatedKontrakId()`
Event listener saat user mengubah jenis kontrak
- Auto-clear tanggal selesai jika "TETAP"
- Log action untuk audit trail
```php
public function updatedKontrakId($value)
{
    if (!$value) {
        return;
    }

    if ($this->isKontrakTetap()) {
        $this->tglselesai_kontrak = null;
        Log::info("User selected TETAP contract type, auto-cleared tglselesai_kontrak");
    }
}
```

#### Method: `save()`
Enhanced dengan logic untuk TETAP kontrak
```php
// If kontrak type is "TETAP" (permanent), force tglselesai to null
if ($this->isKontrakTetap()) {
    $tglselesai_kontrak = null;
    Log::info("Contract type is TETAP, tglselesai_kontrak set to null");
}
```

### 2. Frontend Changes - Form Modal

**File:** `resources/views/livewire/admin/karyawan/kontrak/index.blade.php`

#### Input: Tanggal Selesai Kontrak
```blade
<div class="space-y-2">
    <label class="text-sm font-medium text-gray-700">
        Tanggal Selesai Kontrak
        @if($this->isKontrakTetap())
            <span class="text-gray-500 text-xs ml-1">(Tidak terbatas - Kontrak Tetap)</span>
        @endif
    </label>
    <input wire:model.live="tglselesai_kontrak" type="date"
        @if($this->isKontrakTetap()) disabled @endif
        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm 
        @if($this->isKontrakTetap()) disabled:bg-gray-100 disabled:cursor-not-allowed @endif">
    
    @if($this->isKontrakTetap())
        <p class="text-xs text-blue-600 mt-1">
            <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Kontrak tetap tidak memiliki tanggal selesai
        </p>
    @endif
</div>
```

**Features:**
- ✅ Input disabled saat kontrak = "TETAP"
- ✅ Gray styling untuk disabled state
- ✅ Info label menunjukkan status "Tidak terbatas"
- ✅ Blue info text menjelaskan mengapa disabled

## 🎯 Behavior

### Scenario 1: Create Kontrak - Jenis TETAP
```
1. User buka form Create
2. Pilih "TETAP" di dropdown Jenis Kontrak
3. Input Tanggal Selesai otomatis:
   ✅ Disabled (tidak bisa diklik/edit)
   ✅ Clear value (jika ada)
   ✅ Show label "Tidak terbatas - Kontrak Tetap"
4. User save
5. Result:
   ✅ tglselesai_kontrak = NULL di database
   ✅ Status = "aktif" (unlimited)
```

### Scenario 2: Create Kontrak - Jenis PKWT (Tidak TETAP)
```
1. User buka form Create
2. Pilih "PKWT" di dropdown Jenis Kontrak
3. Input Tanggal Selesai:
   ✅ Enabled (bisa di-edit)
   ✅ Dapat menerima input tanggal
4. User set tanggal: 2025-12-31
5. User save
6. Result:
   ✅ tglselesai_kontrak = 2025-12-31
   ✅ Status auto-sync dari tanggal
```

### Scenario 3: Edit Kontrak PKWT → Ubah ke TETAP
```
1. Edit kontrak dengan jenis "PKWT"
2. Current: tglselesai = 2025-12-31
3. Change jenis kontrak: PKWT → TETAP
4. Result:
   ✅ Tanggal Selesai field disabled
   ✅ Value auto-cleared (null)
   ✅ Sisa kontrak: "Tidak terbatas"
5. User save
6. Database:
   ✅ tglselesai_kontrak = NULL
```

### Scenario 4: Tanggal Selesai Sudah Lewat
```
1. User create/edit kontrak (jenis PKWT)
2. Set tglselesai = 2025-11-01 (sudah lewat)
3. User save
4. Result:
   ✅ Status otomatis jadi "selesai"
   ✅ Sisa kontrak: "Sudah berakhir"
5. Next login/refresh:
   ✅ Status tetap "selesai"
   ✅ Field tergray (readonly)
```

### Scenario 5: Display Sisa Kontrak - TETAP vs PKWT
```
Dalam tabel list:

Kontrak TETAP (tglselesai = NULL):
┌─────────────────────────┐
│ Tanggal: -              │
│ Sisa: Tidak terbatas    │
│ (Gray badge)            │
└─────────────────────────┘

Kontrak PKWT (tglselesai = 2025-12-31):
┌─────────────────────────┐
│ Tanggal: 01 Nov 2025    │
│ Sisa: 19 hari tersisa   │
│ (Green badge)           │
└─────────────────────────┘
```

## 🧪 Testing Checklist

### Test 1: Create Kontrak Jenis TETAP
```
✅ Select "TETAP" di Jenis Kontrak
✅ Tanggal Selesai field disabled (grayed out)
✅ Label shows "Tidak terbatas - Kontrak Tetap"
✅ Blue info text visible
✅ Cannot click/edit tanggal field
✅ Save sukses, tglselesai = NULL di DB
✅ Table shows "Tidak terbatas"
```

### Test 2: Create Kontrak Jenis PKWT
```
✅ Select "PKWT" di Jenis Kontrak
✅ Tanggal Selesai field ENABLED
✅ Can input/edit tanggal
✅ Set tanggal: 2025-12-31
✅ Save sukses
✅ Table shows: "01 Des 2025 s/d" + sisa hari
```

### Test 3: Edit PKWT → Change ke TETAP
```
✅ Edit existing PKWT contract
✅ Change jenis kontrak: PKWT → TETAP
✅ Tanggal field auto-disabled
✅ Value auto-cleared
✅ Save sukses
✅ DB: tglselesai = NULL
✅ Table: "Tidak terbatas"
```

### Test 4: Edit TETAP → Change ke PKWT
```
✅ Edit existing TETAP contract
✅ Change jenis kontrak: TETAP → PKWT
✅ Tanggal field auto-enabled
✅ Can input tanggal selesai
✅ Set tanggal: 2025-12-31
✅ Save sukses
✅ Table: Shows tanggal + sisa hari
```

### Test 5: Sisa Kontrak Display
```
✅ TETAP contract tanpa tanggal:
   - Table shows "Tidak terbatas" (gray)
   - Detail page shows "Tidak terbatas"
   
✅ PKWT dengan tanggal masa depan:
   - Table shows "19 hari tersisa" (green)
   - Correct calculation of remaining days
   
✅ PKWT dengan tanggal lewat:
   - Table shows "Sudah berakhir" (red)
   - Status automatically "selesai"
```

### Test 6: Status Auto-Sync
```
✅ Create kontrak PKWT
✅ Set tglselesai = kemarin (past date)
✅ Save
✅ Status auto-set to "selesai" ✅
✅ Table shows "Sudah berakhir"
✅ Cannot edit when expired
```

## 📝 Files Modified

| File | Changes |
|------|---------|
| `app/Livewire/Admin/Karyawan/Kontrak/Index.php` | +3 methods, +1 updater, +1 logic in save() |
| `resources/views/.../kontrak/index.blade.php` | Updated tanggal selesai input HTML |

## 🔍 Key Logic Points

1. **`isKontrakTetap()`** - Central check for TETAP type
2. **`updatedKontrakId()`** - Auto-clears date when user selects TETAP
3. **`save()` method** - Ensures null date for TETAP before DB save
4. **Form validation** - Allows null tglselesai for TETAP contracts
5. **Display logic** - Shows "Tidak terbatas" for null dates

## ⚠️ Edge Cases Handled

| Edge Case | Handling |
|-----------|----------|
| User types date then selects TETAP | Auto-clear in updatedKontrakId() |
| User selects TETAP then back to PKWT | Field re-enabled, can input date |
| Edit TETAP → should stay null | Backend enforces in save() |
| Export PDF for TETAP | Shows "Tidak terbatas" in report |
| Duplicate active contract rule | Works with null dates |

## 📊 Database Impact

**No migration needed!** Existing `tglselesai_kontrak` column is already nullable.

Behavior:
- TETAP contracts: `tglselesai_kontrak = NULL`
- PKWT contracts: `tglselesai_kontrak = '2025-12-31'`

## 🚀 Status: READY FOR TESTING

All features implemented and PHP syntax verified ✅

