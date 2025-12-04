# 📋 Dokumentasi Permission untuk Tab-Tab Karyawan

## Struktur Permission (Granular Approach)

Setiap tab CRUD karyawan memiliki 4 permission:
- `karyawan_{tab}.view` - Melihat data
- `karyawan_{tab}.create` - Membuat data baru
- `karyawan_{tab}.edit` - Mengedit data
- `karyawan_{tab}.delete` - Menghapus data

## Daftar Tab dan Permission

### 1. Pendidikan
- `karyawan_pendidikan.view` - Lihat riwayat pendidikan
- `karyawan_pendidikan.create` - Tambah riwayat pendidikan
- `karyawan_pendidikan.edit` - Edit riwayat pendidikan
- `karyawan_pendidikan.delete` - Hapus riwayat pendidikan

### 2. Organisasi
- `karyawan_organisasi.view` - Lihat keanggotaan organisasi
- `karyawan_organisasi.create` - Tambah keanggotaan organisasi
- `karyawan_organisasi.edit` - Edit keanggotaan organisasi
- `karyawan_organisasi.delete` - Hapus keanggotaan organisasi

### 3. Pekerjaan
- `karyawan_pekerjaan.view` - Lihat riwayat pekerjaan
- `karyawan_pekerjaan.create` - Tambah riwayat pekerjaan
- `karyawan_pekerjaan.edit` - Edit riwayat pekerjaan
- `karyawan_pekerjaan.delete` - Hapus riwayat pekerjaan

### 4. Keluarga
- `karyawan_keluarga.view` - Lihat data keluarga
- `karyawan_keluarga.create` - Tambah anggota keluarga
- `karyawan_keluarga.edit` - Edit data keluarga
- `karyawan_keluarga.delete` - Hapus data keluarga

### 5. Bahasa
- `karyawan_bahasa.view` - Lihat kemampuan bahasa
- `karyawan_bahasa.create` - Tambah bahasa
- `karyawan_bahasa.edit` - Edit kemampuan bahasa
- `karyawan_bahasa.delete` - Hapus bahasa

### 6. Sertifikasi
- `karyawan_sertifikasi.view` - Lihat sertifikasi
- `karyawan_sertifikasi.create` - Tambah sertifikasi
- `karyawan_sertifikasi.edit` - Edit sertifikasi
- `karyawan_sertifikasi.delete` - Hapus sertifikasi

### 7. Pelatihan
- `karyawan_pelatihan.view` - Lihat riwayat pelatihan
- `karyawan_pelatihan.create` - Tambah pelatihan
- `karyawan_pelatihan.edit` - Edit pelatihan
- `karyawan_pelatihan.delete` - Hapus pelatihan

### 8. Prestasi
- `karyawan_prestasi.view` - Lihat penghargaan/prestasi
- `karyawan_prestasi.create` - Tambah prestasi
- `karyawan_prestasi.edit` - Edit prestasi
- `karyawan_prestasi.delete` - Hapus prestasi

### 9. Dokumen
- `karyawan_dokumen.view` - Lihat dokumen
- `karyawan_dokumen.create` - Upload dokumen
- `karyawan_dokumen.edit` - Edit dokumen
- `karyawan_dokumen.delete` - Hapus dokumen

### 10. Bank
- `karyawan_bank.view` - Lihat data bank
- `karyawan_bank.create` - Tambah rekening bank
- `karyawan_bank.edit` - Edit data bank
- `karyawan_bank.delete` - Hapus data bank

---

## Role Assignment

### Super Admin
✅ Akses penuh semua permission

### Admin
✅ Akses penuh semua permission (kecuali settings)

### Manager
- ✅ View, Create, Edit semua tab (tanpa delete)
- ❌ Delete data

### Staff
**Edit Permission (view + edit):**
- Pendidikan
- Keluarga
- Bahasa
- Bank

**View Only:**
- Organisasi
- Pekerjaan
- Sertifikasi
- Pelatihan
- Prestasi
- Dokumen

---

## Implementasi di Component/View

### 1. Hide/Show Tab berdasarkan Permission

```blade
@php
    $tabs = [
        'pendidikan' => [
            'label' => 'Pendidikan',
            'permission' => 'karyawan_pendidikan.view',
        ],
        'organisasi' => [
            'label' => 'Organisasi',
            'permission' => 'karyawan_organisasi.view',
        ],
        // ... dst
    ];
@endphp

@foreach ($tabs as $key => $tab)
    @can($tab['permission'])
        <a href="{{ route('karyawan.profile', [$karyawan->id, $key]) }}">
            {{ $tab['label'] }}
        </a>
    @endcan
@endforeach
```

### 2. Automatic Permission Check di Component (SUDAH DIIMPLEMENTASI)

Semua 12 tab component sudah menggunakan **HasTabPermission trait** yang otomatis:

#### Di Mount Method:
```php
public function mount($karyawan = null)
{
    // Otomatis cek permission view
    $this->authorizeView();
    
    // ... rest of code
}
```

**Apa yang terjadi:**
- Jika user tidak punya `karyawan_pendidikan.view` → abort 403
- Jika user punya permission → lanjut ke component

#### Di Save Method:
```php
public function save()
{
    try {
        // Otomatis cek permission create atau edit
        if ($this->isEdit) {
            $this->authorizeEdit();  // Check karyawan_pendidikan.edit
        } else {
            $this->authorizeCreate();  // Check karyawan_pendidikan.create
        }
        
        // ... rest of code
    } catch (ValidationException $e) {
        // Error message otomatis di-throw
    }
}
```

#### Di Delete Method:
```php
public function delete()
{
    try {
        // Otomatis cek permission delete
        $this->authorizeDelete();  // Check karyawan_pendidikan.delete
        
        // ... rest of code
    } catch (ValidationException $e) {
        // Error message otomatis di-throw
    }
}
```

### 3. Disable Form Input berdasarkan Permission

```blade
<!-- Edit form di tab -->
<input wire:model="pendidikan_name" type="text"
    {{ !$this->canEdit() ? 'disabled' : '' }}
    class="{{ !$this->canEdit() ? 'bg-gray-100 opacity-60' : '' }}">
```

### 4. Hide/Show Tombol Action

```blade
<!-- Tombol Create -->
@if($this->canCreate())
    <button class="btn-primary">+ Tambah Pendidikan</button>
@endif

<!-- Tombol Edit -->
@if($this->canEdit())
    <button class="btn-warning">Edit</button>
@endif

<!-- Tombol Delete -->
@if($this->canDelete())
    <button class="btn-danger">Delete</button>
@endif
```

### 5. Methods yang Tersedia di Component

Setiap component yang menggunakan `HasTabPermission` memiliki methods:

```php
// Check methods (return boolean)
$this->canView()    // Check karyawan_{tab}.view
$this->canCreate()  // Check karyawan_{tab}.create
$this->canEdit()    // Check karyawan_{tab}.edit
$this->canDelete()  // Check karyawan_{tab}.delete

// Authorization methods (throw exception jika tidak authorized)
$this->authorizeView()    // Throw 403 jika tidak authorized
$this->authorizeCreate()  // Throw ValidationException jika tidak authorized
$this->authorizeEdit()    // Throw ValidationException jika tidak authorized
$this->authorizeDelete()  // Throw ValidationException jika tidak authorized
```

---

## Component yang Sudah Diimplementasi ✅

Semua 12 component tab sudah menggunakan trait dan authorization:

1. ✅ **Pendidikan** - `App\Livewire\Admin\Karyawan\Tab\Pendidikan\Index`
2. ✅ **Organisasi** - `App\Livewire\Admin\Karyawan\Tab\Organisasi\Index`
3. ✅ **Pekerjaan** - `App\Livewire\Admin\Karyawan\Tab\Pekerjaan\Index`
4. ✅ **Keluarga** - `App\Livewire\Admin\Karyawan\Tab\Keluarga\Index`
5. ✅ **Bahasa** - `App\Livewire\Admin\Karyawan\Tab\Bahasa\Index`
6. ✅ **Sertifikasi** - `App\Livewire\Admin\Karyawan\Tab\Sertifikasi\Index`
7. ✅ **Pelatihan** - `App\Livewire\Admin\Karyawan\Tab\Pelatihan\Index`
8. ✅ **Prestasi** - `App\Livewire\Admin\Karyawan\Tab\Prestasi\Index`
9. ✅ **Dokumen** - `App\Livewire\Admin\Karyawan\Tab\Dokumen\Index`
10. ✅ **Bank** - `App\Livewire\Admin\Karyawan\Tab\Bank\Index`
11. ✅ **Kontrak** - `App\Livewire\Admin\Karyawan\Tab\Kontrak\Index`
12. ✅ **Jabatan** - `App\Livewire\Admin\Karyawan\Tab\Jabatan\Index`

---

### 3. Disable Form Input berdasarkan Permission

```blade
<!-- Edit form di tab -->
<input wire:model="pendidikan_name" type="text"
    {{ !auth()->user()->hasPermissionTo('karyawan_pendidikan.edit') ? 'disabled' : '' }}
    class="{{ !auth()->user()->hasPermissionTo('karyawan_pendidikan.edit') ? 'bg-gray-100 opacity-60' : '' }}">
```

### 4. Hide/Show Tombol Action

```blade
<!-- Tombol Create -->
@can('karyawan_pendidikan.create')
    <button class="btn-primary">+ Tambah Pendidikan</button>
@endcan

<!-- Tombol Edit -->
@can('karyawan_pendidikan.edit')
    <button class="btn-warning">Edit</button>
@endcan

<!-- Tombol Delete -->
@can('karyawan_pendidikan.delete')
    <button class="btn-danger">Delete</button>
@endcan
```

---

## Contoh Skenario Penggunaan

### Skenario 1: Staff Edit Profil Sendiri
```
Staff User membuka profile mereka sendiri:
- Lihat semua tab ✅
- Edit Pendidikan, Keluarga, Bahasa, Bank ✅
- Edit/Delete Organisasi, Pekerjaan ❌
```

### Skenario 2: Manager Review Data Karyawan
```
Manager membuka profile karyawan:
- Lihat semua tab ✅
- Edit semua data ✅
- Delete semua data ❌
```

### Skenario 3: Admin Full Access
```
Admin membuka profile karyawan:
- Lihat semua tab ✅
- Create/Edit/Delete semua data ✅
```

---

## Testing Permission

```bash
# Test permission di tinker
php artisan tinker

# Cek permission user
$user = User::find(1);
$user->hasPermissionTo('karyawan_pendidikan.view');
$user->hasPermissionTo('karyawan_pendidikan.edit');

# Cek role
$user->hasRole('staff');
$user->getRoleNames();

# Cek all permissions for role
Role::findByName('staff')->permissions;
```

---

## Tips & Best Practices

1. **Granular Control**: Gunakan permission ini untuk kontrol yang fine-grained
2. **Consistent Naming**: Selalu gunakan pattern `karyawan_{tab}.{action}`
3. **Audit Trail**: Log setiap create/edit/delete action
4. **User Feedback**: Berikan pesan jelas jika user tidak memiliki permission
5. **Default Safe**: Selalu gunakan `@can` untuk hide dangerous operations

---

## Maintenance

Jika menambah tab baru:
1. Tambah 4 permission di PermissionSeeder
2. Re-seed: `php artisan db:seed --class=PermissionSeeder`
3. Update role assignments sesuai kebutuhan
4. Implementasi permission check di component/view
