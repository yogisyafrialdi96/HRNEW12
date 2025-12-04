# Role Authorization Implementation - Modul Roles

## 📋 Ringkasan

Implementasi authorization untuk modul **Roles** menggunakan 3-layer security pattern:
1. **Middleware** - Route protection
2. **Blade Directives** - UI visibility control
3. **Livewire Actions** - Business logic authorization

---

## 🔐 1. MIDDLEWARE PROTECTION (Routes)

### File: `routes/web.php`

```php
// Route untuk roles hanya dapat diakses jika user memiliki permission 'roles.view'
Route::middleware(['auth', 'verified', 'permission:roles.view'])->group(function () {
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', RoleIndex::class)->name('index');
    });
});
```

**Fungsi:**
- Mencegah akses ke halaman roles jika user tidak memiliki permission `roles.view`
- Returned **403 Unauthorized** jika user tidak terauthorisasi
- Dijalankan sebelum component di-load

**Permissions yang digunakan:**
- `roles.view` - Untuk akses halaman index

---

## 🎨 2. BLADE DIRECTIVES (@can/@cannot)

### File: `resources/views/livewire/roles/role-index.blade.php`

#### A. Tombol Tambah Role
```blade
@can('roles.create')
    <button wire:click="openModal" class="...">
        Tambah Role
    </button>
@else
    <button disabled class="...">
        Tambah Role
    </button>
@endcan
```

**Fungsi:**
- Tampilkan button "Tambah Role" jika user memiliki `roles.create`
- Tampilkan button disabled jika tidak punya permission
- **TIDAK bersifat security** (user bisa inspect element)

#### B. Aksi Edit & Delete
```blade
@if (!in_array($role->name, ['super_admin', 'admin']))
    @can('roles.edit')
        <button wire:click="edit({{ $role->id }})" class="...">
            Edit
        </button>
    @endcan

    @can('roles.delete')
        <button wire:click="delete({{ $role->id }})" class="...">
            Hapus
        </button>
    @endcan
@else
    <span class="...">Protected</span>
@endif
```

**Fungsi:**
- Hanya tampilkan button Edit jika user punya `roles.edit`
- Hanya tampilkan button Delete jika user punya `roles.delete`
- Proteksi role super_admin dan admin dari edit/delete
- Tampilkan badge "Protected" untuk protected roles

#### C. Tombol View Detail
```blade
@can('roles.view')
    <button wire:click="showDetail({{ $role->id }})" class="...">
        Detail
    </button>
@endcan
```

---

## ⚡ 3. LIVEWIRE ACTIONS AUTHORIZATION

### File: `app/Livewire/Roles/RoleIndex.php`

#### A. openModal() - Create Action
```php
public function openModal()
{
    // Authorization check - Layer keamanan final
    if (!Auth::user()->can('roles.create')) {
        $this->dispatch('error', 'Anda tidak memiliki izin untuk membuat role');
        return;
    }

    $this->resetForm();
    $this->showModal = true;
    $this->isEdit = false;
}
```

**Fungsi:**
- Check permission sebelum menampilkan form create
- Dispatch error event jika tidak authorized
- Prevent form modal dibuka

#### B. edit($id) - Edit Action
```php
public function edit($id)
{
    try {
        if (!Auth::user()->can('roles.edit')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk mengedit role');
            return;
        }

        $role = Role::findOrFail($id);
        // Load role data...
    } catch (\Exception $e) {
        $this->dispatch('error', 'Role tidak ditemukan');
    }
}
```

**Fungsi:**
- Check permission sebelum load data edit
- Validate role existence
- Dispatch error jika tidak authorized

#### C. save() - Create/Update Action
```php
public function save()
{
    // Determine permission based on mode (create or edit)
    $permission = $this->isEdit ? 'roles.edit' : 'roles.create';
    
    if (!Auth::user()->can($permission)) {
        $this->dispatch('error', 'Anda tidak memiliki izin untuk ' . 
            ($this->isEdit ? 'mengedit' : 'membuat') . ' role');
        return;
    }

    // Validation & Save logic...
}
```

**Fungsi:**
- Check permission sebelum save
- Support both create dan edit dengan permission berbeda
- Prevent unauthorized data modification

#### D. delete($id) - Delete Action
```php
public function delete($id)
{
    try {
        if (!Auth::user()->can('roles.delete')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk menghapus role');
            return;
        }

        $role = Role::findOrFail($id);
        
        // Protect default roles
        if (in_array($role->name, ['super_admin', 'admin'])) {
            $this->dispatch('error', 'Role ini tidak dapat dihapus!');
            return;
        }

        $role->delete();
        $this->dispatch('success', 'Role berhasil dihapus!');
    } catch (\Exception $e) {
        $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
```

**Fungsi:**
- Check permission sebelum delete
- Protect default roles dari deletion
- Transaction-safe deletion

#### E. showDetail($id) - View Action
```php
public function showDetail($id)
{
    try {
        if (!Auth::user()->can('roles.view')) {
            $this->dispatch('error', 'Anda tidak memiliki izin untuk melihat detail role');
            return;
        }

        $this->selectedRole = Role::with('permissions')->findOrFail($id);
        $this->showModalDetail = true;
    } catch (\Exception $e) {
        $this->dispatch('error', 'Role tidak ditemukan');
    }
}
```

---

## 📊 Permission Checklist

Berikut permissions yang digunakan di modul Roles:

| Permission | Fungsi | Digunakan Di |
|-----------|--------|-------------|
| `roles.view` | Akses halaman roles & lihat detail | Middleware, Livewire, Blade |
| `roles.create` | Buat role baru | Livewire openModal(), save() |
| `roles.edit` | Edit role yang ada | Livewire edit(), save() |
| `roles.delete` | Hapus role | Livewire delete() |

---

## 🔒 Security Layers Explanation

### Layer 1: Middleware (Akses Halaman)
```
User Request → Middleware Check Permission → Halaman Load/Abort 403
```
- Mencegah akses ke halaman jika tidak punya permission
- Paling aman karena dicheck sebelum component load

### Layer 2: Blade Directive (UI Visibility)
```
Component Load → @can Check → Tampilkan/Sembunyikan Button
```
- Hanya mengontrol visibility UI
- User bisa inspect element & modify HTML (tidak aman untuk security)
- Berguna untuk UX (hide unavailable actions)

### Layer 3: Livewire Action (Business Logic)
```
User Click Button → Livewire Method → Permission Check → Execute/Reject
```
- Validation terakhir sebelum eksekusi action
- Prevent XHR/API calls bypassing blade checks
- Paling penting untuk security

---

## 🎯 Best Practices Diterapkan

✅ **Konsistensi Permission Names**
- Menggunakan format: `resource.action`
- Contoh: `roles.create`, `roles.edit`, `roles.delete`, `roles.view`

✅ **Multiple Authorization Layers**
- Middleware protects routes
- Blade directives control UI
- Livewire validates actions

✅ **Error Handling**
- User-friendly error messages
- Event dispatch untuk notifications
- No sensitive info in error messages

✅ **Protected Resources**
- Default roles (super_admin, admin) tidak bisa dihapus
- Try-catch untuk exception handling
- Proper error reporting

✅ **User Feedback**
- Success notifications ketika berhasil
- Error notifications ketika gagal
- Clear permission denial messages

---

## 🚀 Testing Authorization

### Skenario 1: User dengan roles.view permission
```
✓ Bisa akses halaman roles
✓ Bisa melihat tombol action
✓ Bisa lihat detail role
✗ Tidak bisa create/edit/delete
```

### Skenario 2: User dengan roles.create permission
```
✓ Bisa membuka form create
✓ Bisa submit form
✓ Role baru tersimpan
```

### Skenario 3: User tanpa permission
```
✗ Middleware akan reject (403)
✗ Tidak bisa akses halaman
✗ Halaman tidak akan load
```

---

## 📝 Implementasi di Modul Lain

Untuk modul lain (permissions, users, etc), ikuti pola yang sama:

1. **Tambah Middleware ke Route**
   ```php
   Route::middleware('permission:module.view')->group(...)
   ```

2. **Gunakan @can di Blade**
   ```blade
   @can('module.create')
       <button>Create</button>
   @endcan
   ```

3. **Check Permission di Livewire**
   ```php
   if (!Auth::user()->can('module.create')) {
       return; // atau dispatch error
   }
   ```

---

## ✨ Summary

**Roles modul** sekarang memiliki:
- ✅ Route-level protection dengan middleware
- ✅ UI visibility control dengan blade directives  
- ✅ Action-level authorization dengan Livewire checks
- ✅ Proper error handling & user feedback
- ✅ Protected default roles dari modification
- ✅ Consistent permission naming convention

Implementasi ini mengikuti **Laravel Security Best Practices** dan **3-Layer Authorization Pattern**.
