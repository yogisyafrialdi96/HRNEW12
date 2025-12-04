# Alpine Expression Error Fix - Roles & Permissions

## 🐛 Problem
```
Alpine Expression Error: sortDirection is not defined
```

Error terjadi di halaman Roles dan Permissions ketika mencoba melakukan sorting.

## 🔍 Root Cause

Dalam file `role-index.blade.php`, digunakan Alpine directive `:class` untuk binding class dynamic:

```blade
:class="{ 'rotate-180': sortDirection === 'asc' }"
```

Masalahnya adalah:
1. Alpine directive `:class` mengakses Alpine component state
2. `sortDirection` adalah Livewire property, bukan Alpine state
3. Alpine tidak bisa mengakses Livewire properties secara langsung

## ✅ Solution

Mengganti Alpine directive `:class` dengan Blade syntax yang benar:

### Before (❌ Error)
```blade
<svg class="w-4 h-4"
    :class="{ 'rotate-180': sortDirection === 'asc' }"
    fill="currentColor">
    <!-- ... -->
</svg>
```

### After (✅ Working)
```blade
<svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
    fill="currentColor">
    <!-- ... -->
</svg>
```

## 📝 Changes Made

### File: `resources/views/livewire/roles/role-index.blade.php`

#### Change 1: Sort by ID header (Line ~68)
```blade
<!-- BEFORE -->
:class="{ 'rotate-180': sortDirection === 'asc' }"

<!-- AFTER -->
class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
```

#### Change 2: Sort by Name header (Line ~83)
```blade
<!-- BEFORE -->
:class="{ 'rotate-180': sortDirection === 'asc' }"

<!-- AFTER -->
class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
```

## 🎯 Key Learning

### Alpine vs Blade/Livewire
- **Alpine** (`:class`, `x-show`, etc.) = JavaScript runtime directives
- **Blade** (`{{ }}`) = PHP runtime directives
- **Livewire** (`wire:click`, etc.) = Livewire runtime directives

### When to use what:
```blade
<!-- For Livewire properties on server side - use Blade -->
class="text-{{ $color }}-500"
class="{{ $condition ? 'hidden' : 'block' }}"

<!-- For Alpine data/state - use Alpine -->
x-data="{ open: false }"
:class="{ 'open': open }"

<!-- Mix both when needed -->
@if ($showButton)
    <button x-data="{ active: false }">
        <span :class="{ 'text-blue-500': active }">Click me</span>
    </button>
@endif
```

## ✨ Result

- ✅ No more Alpine Expression Error
- ✅ Sorting works correctly
- ✅ Rotation animation works on sort headers
- ✅ Properties display correctly

## 🧪 Testing

1. Open `/admin/roles`
2. Click on "No" column header - should rotate icon
3. Click on "Nama Role" column header - should rotate icon
4. No console errors should appear

---

**Status**: ✅ FIXED  
**Date**: 2025-12-01  
**Affected Components**: Roles, Permissions  
**Severity**: Medium (UI issue, not data issue)
