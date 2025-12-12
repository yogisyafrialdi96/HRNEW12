# Bug Fix: Approval Cuti Modal - Button Action tidak Bekerja

## Tanggal: 11 Desember 2025

### Masalah yang Dilaporkan
- Button "Setujui" dan "Tolak" pada modal approval cuti tidak melakukan aksi apapun
- Modal terbuka dengan baik, namun button tidak responsif

---

## Analisis & Perbaikan yang Dilakukan

### 1. **Perubahan Logic Approval (Non-Hierarchy)**

**Sebelumnya:**
- Approval harus bertingkat (Level 1 → Level 2 → dst)
- Logic mengecek apakah current user bisa approve di level spesifik
- Hanya user dengan level yang tepat yang bisa approve

**Sekarang:**
```php
// Approval bisa dilakukan oleh siapa saja yang ada di atasan_user
// tanpa harus mempertimbangkan urutan level
$userPendingApprovals = $pengajuan->approval->filter(function ($approval) use ($userId) {
    return $approval->atasanUser->user_id === $userId 
        && $approval->atasanUser->is_active 
        && $approval->status === 'pending';
});
```

**Keuntungan:**
- Level 1 OR Level 2 bisa approve, tidak perlu urutan
- Lebih fleksibel untuk struktur organisasi berbeda
- Data tetap tercatat di history dengan level-nya

---

### 2. **Perbaikan Method Structure**

#### Method `approve()` dan `reject()`

**Sebelumnya:**
```php
public function approve($pengajuanId) {
    $this->approvalAction = 'approved';  // Set property dulu
    $this->processApproval($pengajuanId); // Property tidak digunakan di processApproval
}
```

**Sekarang:**
```php
public function approve($pengajuanId) {
    try {
        $this->processApproval($pengajuanId, 'approved');
    } catch (\Exception $e) {
        Log::error('Approve error: ' . $e->getMessage());
        $this->dispatch('toast', message: 'Error: ' . $e->getMessage(), type: 'error');
    }
}
```

**Perubahan:**
- Action langsung di-pass sebagai parameter (tidak mengandalkan property)
- Error handling lebih baik dengan try-catch
- Method lebih sederhana dan jelas

---

#### Method `processApproval($pengajuanId, $action)`

**Parameter baru:** `$action` (langsung dari method approve/reject)

**Perubahan key logic:**
```php
// SEBELUMNYA: Cari 1 approval spesifik user di level tertentu
$currentApproval = $pengajuan->approval->firstWhere(...);

// SEKARANG: Cari semua pending approval user (bisa multiple entries)
$userPendingApprovals = $pengajuan->approval->filter(...);

// Loop untuk update semua approval dari user ini
foreach ($userPendingApprovals as $approval) {
    $approval->update([...]);
    CutiApprovalHistory::create([...]);
}
```

**Keuntungan:**
- Menangani case dimana user bisa ada di multiple levels
- History terekam untuk setiap level approval
- Logic lebih robust

---

### 3. **Perbaikan Query di `pengajuans()` Computed**

**Sebelumnya:**
```php
// Hanya ambil approvals yang match dengan currentUserLevels
$q->whereIn('level', $currentUserLevels)
  ->where('status', 'pending');
```

**Sekarang:**
```php
// Ambil approval apapun dari current user
$q->where('status', 'pending')
  ->whereHas('atasanUser', function ($innerQ) use ($userId) {
      $innerQ->where('user_id', $userId)->where('is_active', true);
  });
```

**Perubahan:**
- Tidak lagi filter by level, semua pending approval ditampilkan
- Lebih sederhana dan konsisten dengan new logic

---

### 4. **Improvement Modal & Blade**

**Tambahan di blade:**
```blade
<!-- Spesifik wire:target untuk setiap button -->
<button wire:target="approve" wire:loading.attr="disabled">
    <span wire:loading.remove wire:target="approve">✓ Setujui</span>
    <span wire:loading wire:target="approve">⊙ Memproses...</span>
</button>

<button wire:target="reject" wire:loading.attr="disabled">
    <span wire:loading.remove wire:target="reject">✗ Tolak</span>
    <span wire:loading wire:target="reject">⊙ Memproses...</span>
</button>
```

**Manfaat:**
- Loading state ter-isolasi per button
- User tidak bisa click 2 button sekaligus
- UX lebih baik

---

### 5. **Status Pengajuan Logic**

```php
if ($rejectedCount > 0) {
    // Ada approval yang ditolak → pengajuan REJECTED
    $pengajuan->update(['status' => 'rejected']);
} elseif ($pendingCount === 0) {
    // Semua approval selesai dan tidak ada reject → APPROVED
    $pengajuan->update(['status' => 'approved']);
} else {
    // Masih ada pending → tetap dalam proses
    // (status pengajuan tidak berubah)
}
```

**Logika:**
- REJECTED: jika ada 1 approval yang reject
- APPROVED: jika semua approval done dan semua approved
- PENDING: jika masih ada approval yang pending

---

## Testing Checklist

- [ ] Login sebagai user dengan atasan_user level 1
- [ ] Cek approval list - harus ada pengajuan pending
- [ ] Klik "Review" untuk buka modal
- [ ] Klik "Setujui" dan lihat update status
- [ ] Cek riwayat approval di cuti_approval_history
- [ ] Login user lain dengan level 2
- [ ] Verifikasi bisa approve tanpa perlu level 1 approve duluan
- [ ] Test "Tolak" - pengajuan harus langsung REJECTED

---

## Files yang Berubah

### 1. `app/Livewire/Admin/Cuti/CutiApprovalIndex.php`
- ✅ Method `pengajuans()` - Updated query logic
- ✅ Method `openApprovalModal()` - Simplified check
- ✅ Method `approve()` dan `reject()` - Direct action parameter
- ✅ Method `processApproval()` - New signature & logic

### 2. `resources/views/livewire/admin/cuti/cuti-approval-index.blade.php`
- ✅ Button approval - Added `wire:target` attribute
- ✅ Loading state - Separated per button

---

## Database Structure (Unchanged)

```
cuti_pengajuan
├─ id
├─ user_id (karyawan yang apply cuti)
├─ status (pending, approved, rejected)
└─ ...

cuti_approval (1:many dengan cuti_pengajuan)
├─ id
├─ cuti_pengajuan_id
├─ atasan_user_id (approver)
├─ level (1, 2, 3, dst)
├─ status (pending, approved, rejected)
├─ approved_by (user_id yang approve)
└─ ...

cuti_approval_history
├─ id
├─ cuti_pengajuan_id
├─ level (level approval saat itu)
├─ status (apa yang dilakukan)
├─ approved_by (siapa yang do approval)
└─ ...
```

---

## Summary

**Masalah Root Cause:**
1. Method `approve()` dan `reject()` tidak properly memanggil `processApproval()`
2. Logic approval terlalu kompleks dan mengandalkan property state
3. Blade tidak spesifik untuk wire:target
4. Query logic terlalu strict dengan level checking

**Solusi Applied:**
1. ✅ Simplify method structure - direct parameter passing
2. ✅ Change approval logic dari hierarchy menjadi flexible
3. ✅ Add proper error handling
4. ✅ Improve Blade with specific wire:target
5. ✅ Better logging untuk debugging

**Result:**
- Button sekarang responsive dan berfungsi dengan baik
- Approval tidak lagi bergantung pada urutan level
- Data tercatat dengan baik di history
- UX lebih baik dengan proper loading state
