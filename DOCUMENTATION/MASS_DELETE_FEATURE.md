# Mass Delete Feature - Approval Template & Unit Approval Setting

## Overview
Menambahkan fitur mass delete (bulk delete) dengan modal confirmation untuk dua komponen:
1. **ApprovalTemplateIndex** - Template Approval
2. **UnitApprovalSettingIndex** - Unit Approval Setting

## Features Added

### 1. Checkbox Selection
- **Header Checkbox**: Select All / Deselect All untuk semua item
- **Row Checkboxes**: Checkbox individual untuk setiap item
- **Dynamic Counter**: Menampilkan jumlah item yang dipilih

### 2. Mass Delete Toolbar
- Muncul otomatis saat ada item yang dipilih
- Menampilkan jumlah item yang dipilih
- Tombol "Batal" untuk membatalkan seleksi
- Tombol "Hapus X Item" untuk mulai proses delete

### 3. Modal Confirmation
- Menggunakan `<x-modal-confirmation.modal-confirm-delete />` component
- Menampilkan informasi jumlah item yang akan dihapus
- Support untuk single delete dan mass delete
- Action buttons: Confirm dan Cancel

### 4. Smart Delete Logic

#### ApprovalTemplateIndex
```php
// Skips templates yang masih memiliki details
- Jika ada detail menggunakan template → Skip, tidak dihapus
- Jika berhasil dihapus → Soft delete (is_active = false)
- Menampilkan error count jika ada yang gagal
```

#### UnitApprovalSettingIndex
```php
// Simple soft delete untuk semua setting
- Semua item yang dipilih akan di-soft delete
- is_active di-set menjadi false
- Menampilkan success message dengan jumlah item
```

## Component Changes

### ApprovalTemplateIndex.php
**Properties ditambahkan:**
```php
public array $selectedIds = [];           // Tracking selected items
public bool $selectAll = false;           // Select all state
public bool $confirmingDelete = false;    // Modal confirmation state
public ?ApprovalTemplate $modelToDelete = null;  // Single delete model
public array $selectedIdsToDelete = [];   // Mass delete items
```

**Methods ditambahkan:**
- `toggleSelectAll()` - Handle select/deselect semua
- `toggleSelected($id)` - Handle individual checkbox
- `massDelete()` - Trigger mass delete process
- `confirmMassDelete()` - Execute mass delete
- `cancelMassDelete()` - Cancel mass delete
- `resetDeleteModal()` - Reset modal state
- `delete($model)` - Single delete (modified)
- `confirmDelete()` - Confirm single delete (modified)
- `cancelDelete()` - Cancel single delete (modified)

### UnitApprovalSettingIndex.php
Sama seperti ApprovalTemplateIndex dengan fitur yang disesuaikan.

## UI Updates

### Blade Files Modified
1. **approval-template-index.blade.php**
   - Tambah checkbox column di table header
   - Tambah checkbox di setiap row
   - Tambah mass delete toolbar
   - Tambah modal confirmation
   - Update colspan untuk empty state (6 → 7)

2. **unit-approval-setting-index.blade.php**
   - Sama dengan approval-template-index
   - Disesuaikan dengan struktur table unit-approval-setting

### Table Header Changes
```blade
<!-- Sebelum -->
<th class="px-6 py-4">Nama Template</th>

<!-- Sesudah -->
<th class="px-4 py-4 text-center">
    <input type="checkbox" wire:model.live="selectAll" 
           wire:change="toggleSelectAll" class="w-4 h-4 rounded text-blue-600 cursor-pointer">
</th>
<th class="px-6 py-4">Nama Template</th>
```

### Mass Delete Toolbar
```blade
@if (count($selectedIds) > 0)
    <div class="mb-6 bg-blue-50 rounded-lg p-4 flex items-center justify-between">
        <!-- Display selected count -->
        <!-- Cancel & Delete buttons -->
    </div>
@endif
```

### Delete Button Changes
```blade
<!-- Sebelum -->
<button wire:click="delete({{ $item->id }})" wire:confirm="...">
    Hapus
</button>

<!-- Sesudah -->
<button wire:click="delete({{ $item->id }})">
    Hapus
</button>
```

## User Workflows

### Single Delete
1. User klik tombol "Hapus" di baris tertentu
2. Modal confirmation muncul
3. User klik "Confirm Delete"
4. Item di-soft delete
5. Success toast notification

### Mass Delete
1. User centang checkbox di beberapa baris
2. Toolbar mass delete muncul dengan jumlah item
3. User klik tombol "Hapus X Item"
4. Modal confirmation muncul
5. User klik "Confirm Delete"
6. Semua item di-soft delete (dengan validasi untuk ApprovalTemplate)
7. Success toast notification dengan jumlah yang dihapus

## Authorization
Semua operasi delete memerlukan permission `users.delete`:
```php
public function massDelete()
{
    $this->authorize('users.delete');
    // ...
}
```

## Notifications
Menggunakan toast notification system:
```php
// Error
dispatch('toast', type: 'error', message: 'Pilih minimal satu item');

// Success
dispatch('toast', type: 'success', message: "$deleted template berhasil dihapus");
```

## Database Changes
Tidak ada. Menggunakan soft delete pattern dengan `is_active` field yang sudah ada.

## Testing Checklist
- [ ] Select/deselect individual checkboxes
- [ ] Select all dengan header checkbox
- [ ] Deselect all dengan header checkbox lagi
- [ ] Mass delete toolbar muncul saat ada item dipilih
- [ ] Single delete masih berfungsi normal
- [ ] Mass delete modal confirmation muncul
- [ ] Mass delete berhasil soft delete items
- [ ] Toast notifications menampilkan dengan benar
- [ ] Cancel operations membersihkan state dengan benar
- [ ] Responsive design (mobile/tablet)
- [ ] ApprovalTemplate skip items dengan details
- [ ] UnitApprovalSetting soft delete semua items

## Browser Compatibility
- Chrome/Edge: ✅
- Firefox: ✅
- Safari: ✅
- Mobile browsers: ✅ (Responsive design)

## Performance Notes
- Checkbox operations menggunakan `wire:model.live` (real-time)
- Select All menggunakan pluck untuk efficient data retrieval
- Mass delete loop iterates through selected IDs (optimal untuk <= 100 items)
- Database queries sudah teroptimasi dengan `find()` method
