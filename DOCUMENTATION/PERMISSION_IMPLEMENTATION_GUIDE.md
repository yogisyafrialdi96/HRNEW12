# 🎯 Quick Reference - Permission Implementation in Views

## Implementasi di View/Blade untuk Tab Visibility

### Menampilkan/Menyembunyikan Tab berdasarkan Permission

```blade
<!-- Di karyawan-profile.blade.php -->
<!-- Minimalist Sticky Tabs -->
<div class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="overflow-x-auto">
        <div class="flex space-x-1 px-6 py-2 min-w-max">
            @foreach ($tabs as $key => $tab)
                @php
                    $isActive = $activeTab === $key;
                    // Tentukan route berdasarkan permission
                    if (auth()->user()->hasPermissionTo('karyawan.view_list')) {
                        $tabRoute = route('karyawan.edit', [$karyawan->id, $key]);
                    } else {
                        $tabRoute = route('karyawan.profile', [$karyawan->id, $key]);
                    }
                @endphp

                <!-- PENTING: Hanya tampilkan tab jika user punya permission view -->
                @can("karyawan_{$key}.view")
                    <a href="{{ $tabRoute }}" wire:navigate
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                              {{ $isActive
                                  ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 shadow-sm'
                                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                        
                        <!-- Icon based on tab -->
                        @switch($tab['icon'])
                            @case('academic-cap')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                </svg>
                            @break
                            @case('users')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479" />
                                </svg>
                            @break
                            <!-- ... dst -->
                        @endswitch

                        <span>{{ $tab['label'] }}</span>
                    </a>
                @endcan
            @endforeach
        </div>
    </div>
</div>
```

---

## Implementasi di Tab Component View

### Contoh untuk Tab Pendidikan

```blade
<!-- pendidikan/index.blade.php -->
<div>
    <!-- Header dengan tombol Create -->
    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold">Riwayat Pendidikan</h3>
        
        <!-- PENTING: Hanya tampilkan tombol create jika user punya permission -->
        @if($this->canCreate())
            <button wire:click="openModal" class="btn btn-primary">
                + Tambah Pendidikan
            </button>
        @endif
    </div>

    <!-- Daftar Pendidikan -->
    <div class="space-y-3">
        @forelse($pendidikanList as $pendidikan)
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-semibold">{{ $pendidikan->nama_institusi }}</h4>
                        <p class="text-sm text-gray-600">{{ $pendidikan->jurusan }}</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <!-- Edit Button -->
                        @if($this->canEdit())
                            <button wire:click="editPendidikan({{ $pendidikan->id }})" 
                                class="btn btn-sm btn-warning">
                                Edit
                            </button>
                        @endif
                        
                        <!-- Delete Button -->
                        @if($this->canDelete())
                            <button wire:click="confirmDelete({{ $pendidikan->id }})" 
                                class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Belum ada data pendidikan.</p>
        @endforelse
    </div>

    <!-- Form Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
                <h2 class="text-xl font-bold mb-4">
                    {{ $isEdit ? 'Edit Pendidikan' : 'Tambah Pendidikan' }}
                </h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Institusi</label>
                        <input wire:model="nama_institusi" type="text" 
                            placeholder="Contoh: Universitas Indonesia"
                            class="w-full px-3 py-2 border rounded-lg">
                        @error('nama_institusi') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Form fields lainnya -->
                    <div class="flex gap-2 justify-end mt-6">
                        <button wire:click="closeModal" type="button" 
                            class="btn btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
```

---

## Strategi Permission Check di Blade

### 1. Using @can Directive (Built-in Laravel)

```blade
<!-- Paling simple dan recommended -->
@can('karyawan_pendidikan.create')
    <button>+ Tambah</button>
@endcan

@can('karyawan_pendidikan.edit')
    <button>Edit</button>
@endcan

@cannot('karyawan_pendidikan.delete')
    <!-- Show message if user doesn't have permission -->
    <p class="text-red-500">Anda tidak dapat menghapus</p>
@endcannot
```

### 2. Using Component Method (Recommended untuk Blade)

```blade
<!-- Menggunakan method dari trait HasTabPermission -->
@if($this->canView())
    <!-- Tab visible -->
@endif

@if($this->canCreate())
    <button>+ Tambah</button>
@endif

@if($this->canEdit())
    <button>Edit</button>
@endif

@if($this->canDelete())
    <button>Hapus</button>
@endif
```

### 3. Using Direct Permission Check

```blade
<!-- Jika perlu flexibility atau custom logic -->
@if(auth()->user()->hasPermissionTo('karyawan_pendidikan.view'))
    <!-- Tab visible -->
@endif
```

---

## Error Handling untuk Permission Denied

### Di Component Level (PHP)

```php
use Illuminate\Validation\ValidationException;

public function save()
{
    try {
        // Check authorization - otomatis throw exception
        if ($this->isEdit) {
            $this->authorizeEdit();
        } else {
            $this->authorizeCreate();
        }
        
        // Process...
        
    } catch (ValidationException $e) {
        // Livewire otomatis handle dan display error message
        return;
    }
}
```

### Error Message yang Ditampilkan

Ketika user tidak memiliki permission, mereka akan melihat:

```
"Anda tidak memiliki akses untuk mengedit pendidikan."
"Anda tidak memiliki akses untuk membuat data pendidikan."
"Anda tidak memiliki akses untuk menghapus pendidikan."
"Anda tidak memiliki akses untuk melihat pendidikan."
```

### Di View - Display Error

```blade
<!-- Error message dari Livewire validation -->
@if($errors->has('error'))
    <div class="bg-red-50 border border-red-200 rounded p-4">
        <p class="text-red-700">{{ $errors->first('error') }}</p>
    </div>
@endif
```

---

## Best Practices

### ✅ DO

```blade
<!-- 1. Always check permission before showing action -->
@if($this->canEdit())
    <button wire:click="edit">Edit</button>
@endif

<!-- 2. Disable input jika read-only -->
<input {{ !$this->canEdit() ? 'disabled' : '' }} />

<!-- 3. Provide feedback jika tidak ada permission -->
@if(!$this->canCreate())
    <p class="text-gray-500">Anda tidak dapat menambah data</p>
@endif

<!-- 4. Hide sensitive buttons -->
@if($this->canDelete())
    <button class="btn-danger">Hapus</button>
@endif
```

### ❌ DON'T

```blade
<!-- 1. Jangan tampilkan tab tanpa cek permission -->
<a href="{{ route('tab') }}">Tab</a>  <!-- SALAH -->

<!-- 2. Jangan biarkan input enabled jika read-only -->
<input /> <!-- SALAH jika user tidak punya edit permission -->

<!-- 3. Jangan hide button tapi tetap process di backend -->
<button wire:click="delete" style="display:none">Delete</button> <!-- SALAH -->
<!-- Backend masih bisa diakses jika ada yang attack -->
```

---

## Testing Permission

### Manual Testing di Blade

```blade
<!-- Debug: Lihat permission apa yang dimiliki user -->
<div class="debug-info bg-gray-100 p-4 rounded text-sm">
    @php
        $user = auth()->user();
        $tabs = ['pendidikan', 'organisasi', 'pekerjaan', 'keluarga', 'bahasa', 'sertifikasi', 'pelatihan', 'prestasi', 'dokumen', 'bank'];
    @endphp
    
    <h4 class="font-bold mb-2">Current Permissions:</h4>
    @foreach($tabs as $tab)
        <div class="space-y-1">
            <p class="font-semibold">{{ ucfirst($tab) }}:</p>
            <p class="ml-4">
                view: {{ $user->hasPermissionTo("karyawan_{$tab}.view") ? '✅' : '❌' }}
                create: {{ $user->hasPermissionTo("karyawan_{$tab}.create") ? '✅' : '❌' }}
                edit: {{ $user->hasPermissionTo("karyawan_{$tab}.edit") ? '✅' : '❌' }}
                delete: {{ $user->hasPermissionTo("karyawan_{$tab}.delete") ? '✅' : '❌' }}
            </p>
        </div>
    @endforeach
</div>
```

---

## Common Patterns

### 1. Conditional Tab Rendering

```blade
@foreach ($tabs as $key => $tab)
    @can("karyawan_{$key}.view")
        <a href="{{ route(...) }}">{{ $tab['label'] }}</a>
    @endcan
@endforeach
```

### 2. Read-Only Form

```blade
<input wire:model="nama" 
    {{ !$this->canEdit() ? 'disabled' : '' }}
    class="{{ !$this->canEdit() ? 'bg-gray-100 opacity-60' : '' }}">
```

### 3. Conditional Button Row

```blade
<div class="flex gap-2">
    @if($this->canEdit())
        <button wire:click="edit">Edit</button>
    @endif
    
    @if($this->canDelete())
        <button wire:click="delete">Delete</button>
    @endif
</div>
```

### 4. Empty State dengan Permission Message

```blade
@if($records->isEmpty())
    <div class="text-center py-8">
        @if($this->canCreate())
            <p class="text-gray-500 mb-4">Belum ada data</p>
            <button class="btn btn-primary">+ Tambah Data</button>
        @else
            <p class="text-gray-500">Belum ada data. Hubungi admin untuk menambah.</p>
        @endif
    </div>
@endif
```

---

## Summary Checklist

- [ ] Semua 12 component tab sudah memiliki HasTabPermission trait ✅
- [ ] Mount method memiliki `authorizeView()` ✅
- [ ] Save method memiliki `authorizeCreate()` / `authorizeEdit()` ✅
- [ ] Delete method memiliki `authorizeDelete()` ✅
- [ ] View menampilkan/menyembunyikan tab berdasarkan @can directive
- [ ] Action buttons (edit, delete) hidden jika tidak punya permission
- [ ] Input field disabled jika user tidak punya edit permission
- [ ] Error message ditampilkan jika user mencoba action yang tidak authorized
