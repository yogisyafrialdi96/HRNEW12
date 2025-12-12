# Cuti Saldo Management - CRUD Complete

## Files Created

### 1. Livewire Component
**File:** `app/Livewire/Admin/Master/CutiSaldoIndex.php`

**Features:**
- ✅ List cuti saldo dengan pagination
- ✅ Search by employee name/email
- ✅ Filter by tahun ajaran
- ✅ Create new cuti saldo
- ✅ Edit existing cuti saldo
- ✅ Delete cuti saldo
- ✅ Validation on all fields
- ✅ Error handling

**Permissions:**
- `master_data.view` - View list
- `master_data.create` - Create new
- `master_data.edit` - Edit
- `master_data.delete` - Delete

**Data Fields:**
- user_id (required)
- tahun_ajaran_id (required)
- cuti_tahunan_awal (required, 0-100)
- cuti_tahunan_terpakai (optional)
- cuti_tahunan_sisa (auto-calculated, readonly)
- cuti_melahirkan_awal (optional)
- cuti_melahirkan_terpakai (optional)
- cuti_melahirkan_sisa (auto-calculated, readonly)
- carry_over_tahunan (optional)
- carry_over_digunakan (optional)
- catatan (optional)

### 2. Blade View
**File:** `resources/views/livewire/admin/master/cuti-saldo-index.blade.php`

**Sections:**
- Header with Add button
- Filter section (search, tahun ajaran filter)
- Data table with:
  - Employee info (name, email)
  - Tahun ajaran
  - Cuti tahunan summary (sisa/awal + terpakai)
  - Cuti melahirkan summary
  - Carry over summary
  - Action buttons (Edit, Delete)
- Pagination
- Modal for Create/Edit:
  - Employee selection (disabled on edit)
  - Tahun ajaran selection (disabled on edit)
  - Cuti tahunan section
  - Cuti melahirkan section
  - Carry over section
  - Catatan textarea
- Delete confirmation modal

**Dark Mode:** ✅ Fully supported

### 3. Route
**File:** `routes/web.php`

**Route:** `/admin/setup/cuti-saldo`
**Name:** `setup.cuti-saldo`
**Middleware:** 
- `permission:master_data.view` (required)

**Access:**
- Admin/Superadmin users with `master_data.view` permission
- Can be extended for HR Manager with proper permissions

---

## Usage

### Accessing Cuti Saldo Management
```
Navigate to: /admin/setup/cuti-saldo
```

### Creating New Cuti Saldo
1. Click "+ Tambah Saldo" button
2. Select employee
3. Select tahun ajaran
4. Enter cuti tahunan awal (required)
5. Optionally enter:
   - Cuti tahunan terpakai
   - Cuti melahirkan data
   - Carry over data
   - Catatan
6. Click "Simpan"

### Editing Cuti Saldo
1. Click "Edit" on desired row
2. Update fields (user_id and tahun_ajaran_id are locked on edit)
3. Click "Perbarui"

### Deleting Cuti Saldo
1. Click "Hapus" on desired row
2. Confirm deletion in modal
3. Click "Hapus" to confirm

### Filtering & Search
- **Search:** Type employee name or email in search box
- **Filter by Tahun Ajaran:** Select from dropdown
- **Reset:** Click "Reset Filter" button

---

## Database

### Table: `cuti_saldo`
- id (PK)
- user_id (FK to users)
- tahun_ajaran_id (FK to master_tahunajaran)
- cuti_tahunan_awal (int)
- cuti_tahunan_terpakai (int)
- cuti_tahunan_sisa (int)
- cuti_melahirkan_awal (int)
- cuti_melahirkan_terpakai (int)
- cuti_melahirkan_sisa (int)
- carry_over_tahunan (int)
- carry_over_digunakan (int)
- catatan (text)
- updated_by (FK to users)
- created_at, updated_at

---

## Validation Rules

```php
user_id: required|exists:users,id
tahun_ajaran_id: required|exists:master_tahunajaran,id
cuti_tahunan_awal: required|integer|min:0|max:100
cuti_tahunan_terpakai: nullable|integer|min:0
cuti_tahunan_sisa: nullable|integer|min:0
cuti_melahirkan_awal: nullable|integer|min:0
cuti_melahirkan_terpakai: nullable|integer|min:0
cuti_melahirkan_sisa: nullable|integer|min:0
carry_over_tahunan: nullable|integer|min:0
carry_over_digunakan: nullable|integer|min:0
catatan: nullable|string|max:500
```

---

## Key Features

✅ **Full CRUD Operations**
- Create, Read, Update, Delete
- Proper error handling

✅ **Search & Filter**
- Search by employee name/email
- Filter by tahun ajaran
- Reset filters

✅ **Pagination**
- 15 items per page
- Laravel pagination links

✅ **Permissions**
- Role-based access control
- Blade-level permission checks

✅ **Responsive Design**
- Mobile-friendly
- Dark mode support

✅ **User Experience**
- Modal dialogs for create/edit
- Confirmation before delete
- Toast notifications for success/error
- Readonly calculated fields (sisa)
- Disabled fields on edit (user_id, tahun_ajaran_id)

---

## Notes

1. **Cuti Tahunan Sisa:** Auto-calculated as `awal - terpakai`
2. **Cuti Melahirkan Sisa:** Auto-calculated as `awal - terpakai`
3. **User & Tahun Ajaran:** Cannot be changed after creation (for data integrity)
4. **Carry Over:** Tracks cuti carried over from previous year
5. **Catatan:** Optional field for notes/remarks

---

## Related Models

- `CutiSaldo` - Main model
- `User` - Employee information
- `TahunAjaran` - Academic year
- `CutiPengajuan` - Uses cuti saldo for validation

---

## Status

✅ **COMPLETE** - Ready for use

