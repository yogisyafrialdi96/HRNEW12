# Filter Kontrak - Quick Reference

## 🎯 Filter Tersedia

| Filter | Options | Fungsi |
|--------|---------|--------|
| **Jenis Kontrak** | Semua / TETAP / PKWT / ... | Filter by contract type |
| **Status Kontrak** | Semua / Aktif / Selesai / Perpanjangan / Dibatalkan | Filter by status |
| **Sisa Kontrak** | Semua / Sudah Berakhir / Akan Berakhir ≤30 hari / Masih Berlaku >30 hari / Tidak Terbatas | Filter by remaining days |
| **Show Deleted** | Toggle Button | Show/Hide deleted records |

## 🚀 Quick Usage

### Find Urgent Renewals (PKWT expiring soon)
```
Jenis Kontrak → PKWT
Status → Aktif
Sisa Kontrak → Akan Berakhir (≤30 hari)
```

### View All Permanent Contracts
```
Jenis Kontrak → TETAP
Status → (any)
Sisa Kontrak → Tidak Terbatas
```

### Check Expired Contracts
```
Jenis Kontrak → (any)
Status → Selesai
Sisa Kontrak → Sudah Berakhir
```

### Restore Deleted Contract
1. Click "Show Deleted" button
2. Find contract in table
3. Click Sync icon → Confirm
4. Click "Show Exist" to return to normal view

### Permanent Delete Contract
1. Click "Show Deleted" button
2. Find contract in table
3. Click Trash icon → Confirm
4. ⚠️ Data permanently removed

## 📍 File Locations

**Backend Logic:**
- `app/Livewire/Admin/Karyawan/Kontrak/Index.php` (Lines 213-219: filter properties, Lines 768-813: query filtering)

**Frontend UI:**
- `resources/views/livewire/admin/karyawan/kontrak/index.blade.php` (Lines 26-88: filter section, Lines 239-271: action buttons)

## 🔧 Add New Filter

### 1. Add Property
```php
// In Index.php, after line 219
public $my_new_filter = '';
```

### 2. Add Query Logic
```php
// In render() method, after other filters
$query->when($this->my_new_filter, function ($q) {
    $q->where('field', $this->my_new_filter);
});
```

### 3. Add UI
```blade
{{-- In index.blade.php, in filter grid --}}
<select wire:model.live="my_new_filter"
    class="px-4 py-2 border border-gray-300 rounded-lg ...">
    <option value="">All Options</option>
    <option value="val1">Option 1</option>
</select>
```

## ⚡ Key Methods

- `jenis_kontrak_filter` - Selected contract type ID
- `status_kontrak_filter` - Selected status value
- `sisa_kontrak_filter` - Selected remaining days category
- `showDeleted` - Toggle soft-deleted records visibility
- `confirmRestore($id)` - Prepare to restore
- `restore()` - Execute restore
- `confirmForceDelete($id)` - Prepare to permanent delete
- `forceDelete()` - Execute permanent delete

## 📋 Test Scenarios

1. ✅ Apply multiple filters simultaneously
2. ✅ Search works with filters (not cleared)
3. ✅ Sort works with filters (not cleared)
4. ✅ Pagination works with filters
5. ✅ Show/Hide deleted toggles correctly
6. ✅ Restore/Delete buttons appear/disappear based on showDeleted
7. ✅ No results message shows when filters have no matches
8. ✅ Mobile responsive layout works

---

**Quick Access:** Bookmark this file for quick filter reference!
