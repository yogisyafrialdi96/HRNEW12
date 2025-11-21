# 🔄 UPDATE: Kontrak TETAP - Allow Edit for Resignation/Retirement

## 📋 Requirement Change

**Original:** Tanggal selesai DISABLED untuk semua operasi (CREATE & EDIT) pada kontrak "TETAP"

**Updated:** 
- **CREATE:** Tanggal selesai DISABLED (default permanent/unlimited)
- **EDIT:** Tanggal selesai ENABLED (user dapat mengubah jika pegawai pensiun/resign)

---

## ✨ Business Logic

### Kontrak TETAP Workflow

```
Scenario 1: CREATE TETAP Kontrak
┌─────────────────────────────────────────┐
│ Pegawai baru dengan kontrak permanent    │
├─────────────────────────────────────────┤
│ Action: Create kontrak type TETAP        │
│ → Tanggal Selesai: DISABLED              │
│ → Auto-clear tanggal                     │
│ → Save: tglselesai_kontrak = NULL        │
│ → Sisa: "Tidak terbatas"                 │
└─────────────────────────────────────────┘

Scenario 2: EDIT TETAP Kontrak (Later: Resign/Retire)
┌─────────────────────────────────────────┐
│ Kontrak sudah 5 tahun (TETAP)            │
├─────────────────────────────────────────┤
│ Event: Pegawai resign/pensiun             │
│ Action: Edit kontrak, set tanggal akhir  │
│ → Tanggal Selesai: ENABLED               │
│ → Can input: 2025-12-31 (resign date)    │
│ → Save: tglselesai_kontrak = 2025-12-31  │
│ → Sisa: "Sudah berakhir" atau "X hari"   │
│ → Status auto: "selesai"                 │
└─────────────────────────────────────────┘
```

---

## 🔧 Implementation

### Backend Changes

**File:** `app/Livewire/Admin/Karyawan/Kontrak/Index.php`

#### Method: `updatedKontrakId()` - UPDATED
```php
public function updatedKontrakId($value)
{
    if (!$value) {
        return;
    }

    // HANYA auto-clear saat CREATE (tidak ada kontrak_karyawan_id)
    if (!$this->isEdit && $this->isKontrakTetap()) {
        $this->tglselesai_kontrak = null;
        Log::info("...CREATE, auto-cleared tglselesai_kontrak");
    }
    // Saat EDIT, tetap biarkan user edit tanggal (untuk pensiun/resign)
    elseif ($this->isEdit && $this->isKontrakTetap()) {
        Log::info("...EDIT, allow user to set end date");
    }
}
```

**Key:** Check `!$this->isEdit` sebelum auto-clear

#### Method: `save()` - UPDATED
```php
// If kontrak type is "TETAP" on CREATE, force tglselesai to null
// On EDIT, allow user to set end date (for resignation/retirement)
if (!$this->isEdit && $this->isKontrakTetap()) {
    $tglselesai_kontrak = null;
    Log::info("Creating TETAP contract type, tglselesai_kontrak set to null");
}
```

**Key:** Force null HANYA pada CREATE (`!$this->isEdit`)

### Frontend Changes

**File:** `resources/views/livewire/admin/karyawan/kontrak/index.blade.php`

#### Input Field - UPDATED
```blade
<input wire:model.live="tglselesai_kontrak" type="date"
    @if($this->isKontrakTetap() && !$isEdit) disabled @endif
    class="... @if($this->isKontrakTetap() && !$isEdit) disabled:bg-gray-100 @endif">
```

**Key:** Disable HANYA jika (`TETAP && !EDIT`)

#### Label & Messages - UPDATED
```blade
<!-- CREATE: Show "(Tidak terbatas - Kontrak Tetap)" -->
@if($this->isKontrakTetap() && !$isEdit)
    <span>(Tidak terbatas - Kontrak Tetap)</span>
    <p class="text-blue-600">Kontrak tetap tidak memiliki tanggal selesai (baru)</p>
@endif

<!-- EDIT: Show orange warning for flexibility -->
@elseif($this->isKontrakTetap() && $isEdit)
    <p class="text-orange-600">
        Kontrak tetap - dapat diedit untuk pensiun/resign
    </p>
@endif
```

---

## 📊 Behavior Matrix

| Mode | Kontrak TETAP | Kontrak PKWT |
|------|----------------|---|
| **CREATE** | | |
| - Tanggal field | 🔴 DISABLED | 🟢 ENABLED |
| - Auto-clear | ✅ Yes | ❌ No |
| - Save value | NULL | date |
| - Display | "Tidak terbatas" | "X hari tersisa" |
| | | |
| **EDIT** | | |
| - Tanggal field | 🟢 ENABLED | 🟢 ENABLED |
| - Can edit | ✅ Yes (resign) | ✅ Yes (extend) |
| - Save value | user input | user input |
| - Display | "X hari" or NULL | "X hari tersisa" |

---

## 🧪 Testing Scenarios

### Test 1: CREATE TETAP Contract ✅
```
1. Click Create
2. Select "TETAP"
3. Tanggal Selesai field: 
   ✅ DISABLED (gray, not clickable)
   ✅ Info: "Kontrak tetap tidak memiliki tanggal selesai (baru)"
4. Save
5. Result: tglselesai = NULL, Sisa = "Tidak terbatas"
```

### Test 2: EDIT TETAP Contract (No Change) ✅
```
1. Edit existing TETAP contract
2. Jenis Kontrak: TETAP
3. Tanggal Selesai field:
   ✅ ENABLED (white, clickable)
   ✅ Info: "Kontrak tetap - dapat diedit untuk pensiun/resign" (orange)
4. Leave date empty
5. Save
6. Result: tglselesai stays NULL
```

### Test 3: EDIT TETAP Contract (Set Date) ✅
```
1. Edit TETAP contract
2. Jenis Kontrak: TETAP
3. Tanggal Selesai field: ENABLED
4. Input date: 2025-12-31 (resign date)
5. Save
6. Result:
   ✅ tglselesai = 2025-12-31
   ✅ Status auto: "selesai"
   ✅ Table: "Sudah berakhir" or "X hari"
```

### Test 4: EDIT TETAP Contract (Change Type) ✅
```
1. Edit TETAP contract
2. Change Jenis: TETAP → PKWT
3. Tanggal field:
   ✅ Info msg gone
   ✅ Field remains enabled
   ✅ Can input/edit date
4. Input date: 2025-12-31
5. Save
6. Result: Normal PKWT contract
```

### Test 5: CREATE then EDIT Flow ✅
```
1. CREATE TETAP contract
   ✅ Tanggal disabled
   ✅ Save with NULL
2. EDIT same contract (later, after resign)
   ✅ Tanggal field ENABLED
   ✅ Set date: 2025-12-31
   ✅ Save with date
3. Result: Contract now has end date
```

---

## 📋 Key Differences

### Old Behavior (Before Update)
```
TETAP Contract:
- CREATE: Tanggal DISABLED
- EDIT:   Tanggal DISABLED ❌ (Problem!)
```

### New Behavior (After Update)
```
TETAP Contract:
- CREATE: Tanggal DISABLED ✅
- EDIT:   Tanggal ENABLED ✅ (Can set resign date)
```

---

## 💡 Why This Makes Sense

1. **Initial Setup:** Employee starts with permanent contract (no end date) → CREATE disables date
2. **Later Event:** Employee resigns/retires after 5 years → EDIT allows setting end date
3. **Flexibility:** Admin can manage life cycle without recreating contract
4. **Data Integrity:** System enforces creation-time (NULL) but allows business changes on edit

---

## 🚀 Status

```
✅ Backend logic updated
✅ Frontend form updated  
✅ PHP syntax verified
✅ Logic sound
🟢 READY FOR TESTING
```

---

## 📝 Test Results

After testing, mark these:

- [ ] TEST 1: CREATE TETAP (tanggal disabled) - PASS/FAIL
- [ ] TEST 2: EDIT TETAP (tanggal enabled, no change) - PASS/FAIL
- [ ] TEST 3: EDIT TETAP (set resignation date) - PASS/FAIL
- [ ] TEST 4: EDIT TETAP (change type to PKWT) - PASS/FAIL
- [ ] TEST 5: CREATE then EDIT flow - PASS/FAIL

**Overall:** ✅ PASS / ❌ FAIL

---

*Last Updated: November 12, 2025*
*Version: 1.1 - Updated for EDIT flexibility*
