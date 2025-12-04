# CRUD Roles dengan Spatie Laravel Permission

## Overview
CRUD Roles yang lengkap menggunakan Spatie Laravel Permission untuk mengelola role dan permission sistem. Dibangun dengan Livewire v3 dan Tailwind CSS.

## Fitur Utama

### 1. **Create Role**
- Input nama role (unique)
- Input deskripsi (opsional)
- Pilih permission dari checkbox list
- Permission di-grouping berdasarkan guard_name
- Validasi otomatis

### 2. **Read/List Roles**
- Tampilan tabel dengan sorting
- Search berdasarkan nama atau deskripsi
- Pagination (10, 25, 50, 100 items per halaman)
- Menampilkan jumlah permission dan user per role
- Role 'super_admin' dan 'admin' dilindungi (tidak bisa diedit/dihapus)

### 3. **Update Role**
- Edit nama role
- Edit deskripsi
- Update permission yang diberikan
- Validasi unique name (kecuali role yang sedang diedit)
- Sinkronisasi permission otomatis

### 4. **Delete Role**
- Soft delete role
- Role 'super_admin' dan 'admin' dilindungi dari penghapusan
- Konfirmasi sebelum penghapusan

### 5. **Detail Modal**
- Lihat informasi lengkap role
- Daftar semua permission yang diberikan
- Daftar user yang memiliki role ini
- Display name dengan formatting yang rapi

## Struktur Database (Spatie)

```
roles
├── id (primary key)
├── name (unique)
├── description
├── guard_name (default: 'web')
├── created_at
└── updated_at

permissions
├── id (primary key)
├── name (unique)
├── description
├── guard_name (default: 'web')
├── created_at
└── updated_at

role_has_permissions (pivot)
├── permission_id (FK)
├── role_id (FK)
└── guard_name

model_has_roles (pivot) - untuk user-role relationship
├── role_id (FK)
├── model_id (FK)
├── model_type
└── guard_name
```

## File Structure

```
app/
├── Livewire/
│   └── Roles/
│       └── RoleIndex.php          # Component utama

resources/
└── views/
    └── livewire/
        └── roles/
            └── role-index.blade.php  # View lengkap
```

## Component Properties

### Public Properties
```php
// Form fields
public $roleId;              // ID role yang di-edit
public $name = '';           // Nama role
public $description = '';    // Deskripsi role
public $selectedPermissions = [];  // Array permission IDs yang dipilih

// Search & filter
public $search = '';         // Query pencarian
public $perPage = 10;        // Items per halaman

// UI states
public $showModal = false;       // Toggle create/edit modal
public $isEdit = false;          // Flag untuk mode edit
public $showModalDetail = false; // Toggle detail modal
public $selectedRole;            // Role untuk detail view
```

### URL Tracked Properties
```php
#[Url]
public string $query = '';           // Pencarian URL state

#[Url(except: 'id')]
public string $sortField = 'id';     // Field sorting

#[Url(except: 'desc')]
public string $sortDirection = 'desc'; // Arah sorting
```

## Public Methods

### CRUD Operations
```php
// Buka modal create
openModal()

// Edit role
edit($id)

// Simpan role (create/update)
save()

// Hapus role
delete($id)

// Lihat detail role
showDetail($id)

// Tutup semua modal
closeModal()
```

### Helper Methods
```php
// Sorting
sortBy($field)

// Search updater
updatedSearch()

// Query builder & render
render()
```

## Validation Rules

```php
'name' => 'required|string|min:2|max:255|unique:roles,name'
// (pada edit: unique:roles,name,{roleId})

'description' => 'nullable|string|max:1000'

'selectedPermissions' => 'array'
```

## Error Handling

Semua operasi dibungkus dalam try-catch dengan DB transactions:
- Validasi input otomatis
- Error messages ditampilkan ke user
- Rollback jika ada error saat save
- Protected roles (super_admin, admin) tidak bisa diedit/dihapus

## Usage Example

### 1. Akses Halaman
```
/admin/roles
```

### 2. Create New Role
```
- Klik tombol "Tambah Role"
- Isi nama role (misal: 'moderator')
- Isi deskripsi (opsional)
- Pilih permission dari checkbox list
- Klik "Buat Role"
```

### 3. Edit Role
```
- Klik tombol Edit pada row
- Ubah data sesuai kebutuhan
- Pilih permission
- Klik "Simpan Perubahan"
```

### 4. Delete Role
```
- Klik tombol Delete
- Konfirmasi penghapusan
```

### 5. Lihat Detail
```
- Klik tombol Detail (mata)
- Lihat info lengkap, permission, dan user
```

## Integration with Users

Untuk assign role ke user:

```php
// Assign single role
$user->assignRole('moderator');

// Assign multiple roles
$user->assignRole(['moderator', 'editor']);

// Revoke role
$user->removeRole('moderator');

// Check role
if ($user->hasRole('moderator')) {
    // ...
}

// Check permission
if ($user->can('edit_posts')) {
    // ...
}
```

## Middleware & Guards

Default guard: 'web'

Untuk melindungi route dengan permission:
```php
Route::get('/admin', function () {
    // ...
})->middleware('role:admin');

Route::get('/posts/{post}/edit', function () {
    // ...
})->middleware('permission:edit_posts');
```

## Permission Seeding

Buat migration untuk membuat permission:

```php
// database/seeders/PermissionSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create(['name' => 'create_roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_roles', 'guard_name' => 'web']);
        
        // Create role
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['create_roles', 'edit_roles', 'delete_roles', 'view_roles']);
    }
}
```

## Features Breakdown

### Table Display
✅ Nomor urut (dengan offset pagination)
✅ Nama role dengan badge
✅ Guard name
✅ Deskripsi
✅ Jumlah permission (blue badge)
✅ Jumlah user (purple badge)
✅ Action buttons (Detail, Edit, Delete)
✅ Protected role indicator (yellow badge)

### Filtering & Search
✅ Search by name atau description
✅ Sort by field (id, name)
✅ Sort direction (asc, desc)
✅ Pagination controls
✅ Configurable items per page

### Form Validation
✅ Client-side error display
✅ Real-time validation feedback
✅ Permission grouping by guard_name
✅ Checkbox array selection

### Modal Features
✅ Create/Edit modal dengan header/footer
✅ Backdrop click to close
✅ Detail modal read-only
✅ Smooth transitions
✅ Dark mode support

## Styling

- **Framework**: Tailwind CSS
- **Colors**: Blue (primary), Purple (badges), Red (delete), Yellow (protected)
- **Dark Mode**: Full support dengan dark: prefix
- **Responsive**: Mobile-first design
- **Icons**: Heroicons inline SVG

## Performance Considerations

✅ Eager loading relationships dengan `->with('permissions')`
✅ Select only needed fields untuk query besar
✅ Paginated results (max 100 items per page)
✅ Efficient permission counting
✅ Database transactions untuk data consistency

## Security

✅ Protected roles (super_admin, admin) tidak bisa diedit/dihapus
✅ User confirmation dialog sebelum delete
✅ Validation pada semua input
✅ Database transactions untuk atomicity
✅ Livewire CSRF protection
✅ User roles/permissions check recommended pada middleware

## Testing Checklist

- [ ] Create role dengan permission
- [ ] Edit role dan ubah permission
- [ ] Delete role
- [ ] Search role
- [ ] Sort by field
- [ ] Pagination works
- [ ] Protected roles tampil dengan warning
- [ ] Detail modal show all info
- [ ] Form validation works
- [ ] Error handling works
- [ ] Dark mode works
- [ ] Responsive design works

## Troubleshooting

### Permission tidak muncul di dropdown
- Pastikan permission sudah dibuat di database
- Jalankan: `php artisan db:seed PermissionSeeder`
- Check guard_name di permissions table

### Edit/Delete tidak bekerja pada role tertentu
- Check apakah role adalah 'super_admin' atau 'admin' (protected)
- Ubah nama role terlebih dahulu jika ingin mengedit

### Pagination tidak reset saat search
- `updatedSearch()` method sudah otomatis reset page
- Check browser cache jika masalah persist

### Role tidak terlihat di list
- Refresh halaman
- Check database connection
- Verify role guard_name = 'web'
