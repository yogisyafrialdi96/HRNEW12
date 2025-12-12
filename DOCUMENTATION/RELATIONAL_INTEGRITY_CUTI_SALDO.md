# Cuti Saldo Relational Integrity Implementation

**Status**: ✅ IMPLEMENTED  
**Date**: 2025-01-xx  
**Phase**: Data Integrity & Synchronization

## Overview

Implementasi mekanisme sinkronisasi otomatis antara **CutiPengajuan** (request cuti) dan **CutiSaldo** (master data quota) untuk memastikan integritas data relasional.

## Masalah yang Diperbaiki

### Sebelum Perbaikan
- ⚠️ Approval workflow tidak mengupdate field `cuti_terpakai` di tabel `cuti_saldo`
- ⚠️ Tidak ada validasi untuk prevent over-allocation cuti
- ⚠️ Saldo tracking incomplete karena synchronization logic missing
- ⚠️ Data consistency antara approval dan saldo tidak terjamin

### Setelah Perbaikan
- ✅ ApprovalService otomatis update `cuti_terpakai` ketika cuti approved
- ✅ Validasi balance check sebelum cuti disubmit
- ✅ Recalculation automatic untuk `cuti_sisa`
- ✅ Transaction-based operation untuk data consistency
- ✅ Error handling untuk invalid states

## Implementasi Detail

### 1. ApprovalService Enhancement

#### File Modified
```
app/Services/ApprovalService.php
```

#### Perubahan pada `approveCuti()` Method

Ketika semua level approval completed, sekarang:

```php
if ($approvedCount == $totalLevels) {
    // Update cuti pengajuan status
    $cutiPengajuan->update([
        'status' => 'approved',
        'updated_by' => $approver->id,
    ]);

    // ✅ NEW: Update CutiSaldo
    if ($cutiPengajuan->cutiSaldo) {
        $cutiSaldo = $cutiPengajuan->cutiSaldo;
        
        if ($cutiPengajuan->jenis_cuti === 'tahunan') {
            // Increment terpakai, decrement sisa
            $cutiSaldo->increment('cuti_tahunan_terpakai', $cutiPengajuan->jumlah_hari);
            $cutiSaldo->decrement('cuti_tahunan_sisa', $cutiPengajuan->jumlah_hari);
        } elseif ($cutiPengajuan->jenis_cuti === 'melahirkan') {
            // Increment terpakai, decrement sisa
            $cutiSaldo->increment('cuti_melahirkan_terpakai', $cutiPengajuan->jumlah_hari);
            $cutiSaldo->decrement('cuti_melahirkan_sisa', $cutiPengajuan->jumlah_hari);
        }
    }
}
```

**Operasi Dilakukan dalam Transaction**:
```php
return DB::transaction(function () use (...) {
    // All operations atomic
    // Rollback jika ada error
});
```

#### New Validation Method: `validateCutiBalance()`

```php
public static function validateCutiBalance(CutiPengajuan $cutiPengajuan): array
```

**Purpose**: Cek apakah sisa saldo cukup untuk approve cuti

**Return**: Array dengan structure:
```php
[
    'valid' => bool,  // true jika cukup, false jika kurang
    'message' => string  // Error message atau success message
]
```

**Logic**:
1. Check apakah CutiSaldo exists untuk user
2. Get sisa dari jenis cuti yang diminta (tahunan atau melahirkan)
3. Compare dengan jumlah_hari yang diminta
4. Return validation result

**Usage**:
```php
$validation = ApprovalService::validateCutiBalance($cutiPengajuan);
if (!$validation['valid']) {
    // Show error message: $validation['message']
}
```

#### New Utility Method: `getUserCutiSaldo()`

```php
public static function getUserCutiSaldo(User $user, TahunAjaran $tahunAjaran): ?CutiSaldo
```

**Purpose**: Get CutiSaldo untuk user tertentu di tahun tertentu

**Return**: CutiSaldo instance atau null jika tidak ada

**Usage**:
```php
$saldo = ApprovalService::getUserCutiSaldo($user, $tahunAjaran);
if ($saldo) {
    echo "Sisa: " . $saldo->cuti_tahunan_sisa;
}
```

### 2. CutiPengajuanIndex Component Enhancement

#### File Modified
```
app/Livewire/Admin/Cuti/CutiPengajuanIndex.php
```

#### Perubahan pada `submit()` Method

**Sebelum**:
```php
public function submit(CutiPengajuan $model)
{
    // ...
    $model->update(['status' => 'pending']);
    // Submit langsung tanpa validasi balance
}
```

**Sesudah**:
```php
public function submit(CutiPengajuan $model)
{
    // ... permission check ...
    
    // ✅ NEW: Validate cuti balance
    $validation = ApprovalService::validateCutiBalance($model);
    if (!$validation['valid']) {
        $this->dispatch('toast', type: 'error', message: $validation['message']);
        return;
    }
    
    $model->update(['status' => 'pending']);
    // Submit hanya jika balance cukup
}
```

**Error Message Example**:
- "Sisa cuti tahunan tidak cukup. Tersedia: 5 hari, diminta: 10 hari"
- "Sisa cuti melahirkan tidak cukup. Tersedia: 0 hari, diminta: 30 hari"

### 3. Import Updates

Added to ApprovalService:
```php
use App\Models\IzinCuti\CutiSaldo;
use App\Models\Master\TahunAjaran;
```

## Database Impact

### Table: `cuti_saldo`

**Fields Updated When Approval Completes**:

1. **For Tahunan Cuti**:
   ```
   cuti_tahunan_terpakai = cuti_tahunan_terpakai + jumlah_hari
   cuti_tahunan_sisa = cuti_tahunan_sisa - jumlah_hari
   updated_at = now()
   ```

2. **For Melahirkan Cuti**:
   ```
   cuti_melahirkan_terpakai = cuti_melahirkan_terpakai + jumlah_hari
   cuti_melahirkan_sisa = cuti_melahirkan_sisa - jumlah_hari
   updated_at = now()
   ```

### No Migration Needed

- Semua fields sudah exist di tabel
- Hanya logic yang ditambahkan, bukan schema change

## Transaction Management

**Atomic Operations**:
```php
DB::transaction(function () {
    // CutiApproval update
    // CutiApprovalHistory insert
    // CutiPengajuan update
    // CutiSaldo update ← NEW
    // All rollback if any error
});
```

## Workflow Integration

### Full Approval Workflow with Saldo Update

```
1. User create cuti request (status: draft)
   └─ CutiSaldo linked but NOT updated

2. User submit cuti (status: pending)
   └─ Validation: Check saldo balance
   └─ If NOT enough → Error, stay draft
   └─ If enough → Continue

3. Level 1 approve
   └─ CutiApproval: pending → approved
   └─ CutiSaldo: NOT updated yet

4. Level 2 approve (if exists)
   └─ CutiApproval: pending → approved
   └─ CutiSaldo: NOT updated yet

5. All levels approved
   └─ CutiPengajuan: pending → approved
   └─ CutiSaldo: ✅ UPDATE terpakai & sisa
   └─ CutiApprovalHistory: Complete audit trail

Final State:
├─ CutiPengajuan.status = 'approved'
├─ CutiSaldo.cuti_tahunan_terpakai += jumlah_hari
├─ CutiSaldo.cuti_tahunan_sisa -= jumlah_hari
└─ All changes in single transaction
```

### Rejection Workflow (No Change)

```
1. Approver reject
   └─ CutiApproval: pending → rejected
   └─ CutiPengajuan: any → rejected
   └─ CutiSaldo: NOT updated
   └─ Reason: Cuti never approved, quota stays intact
```

## Error Handling

### Validation Scenarios

| Scenario | Error Message | Action |
|----------|---------------|--------|
| Saldo tidak ada | "Saldo cuti tidak ditemukan..." | Prevent submit |
| Tidak cukup terpakai | "Sisa cuti tahunan tidak cukup. Tersedia: X, diminta: Y" | Prevent submit |
| DB transaction error | "Error: [exception message]" | Rollback, retry |
| Approval already done | "Tidak ada pending approval..." | Skip approval |

## Testing Scenarios

### Test 1: Normal Approval with Balance Update
```
1. Create cuti request: 5 days
2. Submit (saldo tahunan: 12 days available)
   ✓ Validation passes
3. Level 1 approve
4. Level 2 approve (if exists)
   ✓ CutiSaldo.cuti_tahunan_terpakai: 0 → 5
   ✓ CutiSaldo.cuti_tahunan_sisa: 12 → 7
```

### Test 2: Prevent Over-allocation
```
1. Create cuti request: 15 days
2. Submit (saldo tahunan: 12 days available)
   ✗ Validation fails
   ✗ Error: "Sisa cuti tahunan tidak cukup. Tersedia: 12 hari, diminta: 15 hari"
   ✗ Status stay 'draft'
3. CutiSaldo NOT updated
```

### Test 3: Multiple Requests
```
1. Create request A: 5 days (saldo: 12)
   → Submit → Approve → saldo: 7
2. Create request B: 10 days (saldo: 7)
   → Submit ✗ Validation fails (need 10, have 7)
3. Create request B: 7 days (saldo: 7)
   → Submit ✓ Validation passes
   → Approve → saldo: 0
```

## Backward Compatibility

### No Breaking Changes

- Existing CutiPengajuan models still work
- Existing routes still work
- Existing components still work
- New validation is non-blocking for existing data

### Migration Path for Existing Data

Jika sudah ada approved cuti sebelumnya yang tidak terupdate saldo:

```php
// Manual fix command (optional)
$approvedCuti = CutiPengajuan::where('status', 'approved')->get();
foreach ($approvedCuti as $cuti) {
    if ($cuti->cutiSaldo && !$cuti->saldo_updated) {
        // Update saldo manually
    }
}
```

## Code Quality

### Type Hints
- Full return type declarations
- Parameter type hints
- Array structure documentation

### Transaction Safety
- All multi-step operations atomic
- Automatic rollback on error
- No orphaned records possible

### Error Messages
- User-friendly messages
- Specific validation errors
- Logged exceptions for debugging

## Summary Table

| Component | Before | After | Status |
|-----------|--------|-------|--------|
| approveCuti() | No saldo update | ✅ Updates terpakai & sisa | DONE |
| submit() in UI | No balance check | ✅ Validates balance | DONE |
| validateCutiBalance() | N/A | ✅ New method | DONE |
| getUserCutiSaldo() | N/A | ✅ New utility | DONE |
| Imports | Missing | ✅ Added | DONE |

## Next Steps

1. ✅ **Completed**: Basic relational integrity
2. ✅ **Completed**: Approval → Saldo synchronization
3. ✅ **Completed**: Balance validation
4. 📋 **Future**: Implement carry-over logic for next year
5. 📋 **Future**: Add audit report for saldo tracking
6. 📋 **Future**: Implement saldo allocation rules

## Files Modified

1. `app/Services/ApprovalService.php`
   - Updated imports (CutiSaldo, TahunAjaran)
   - Enhanced approveCuti() with saldo update
   - Added validateCutiBalance() method
   - Added getUserCutiSaldo() utility method

2. `app/Livewire/Admin/Cuti/CutiPengajuanIndex.php`
   - Enhanced submit() with balance validation

## Related Documentation

- `APPROVAL_SYSTEM_ARCHITECTURE.md` - Approval workflow design
- `app/Models/IzinCuti/CutiSaldo.php` - Model relationships
- `app/Models/IzinCuti/CutiPengajuan.php` - Pengajuan model
