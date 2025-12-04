# Quick Reference - CRUD Roles Spatie Laravel

## Setup Initial Data

```bash
# Run seeder untuk create permissions dan roles
php artisan db:seed PermissionSeeder

# Atau seed semua
php artisan db:seed
```

## Component API Reference

### Opening Modals

```php
// Buka modal create role
$this->dispatch('openModal');
// atau via wire:click="openModal"

// Buka modal lihat detail
$this->dispatch('showDetail', id: 1);
// atau via wire:click="showDetail({{ $role->id }})"
```

### Saving Data

```php
// Save otomatis di trigger saat form submit
// Validasi: nama role unique, description optional, permission array

$this->dispatch('save');
// atau via wire:click="save" on button
```

### Searching & Filtering

```livewire
<!-- Search field -->
<input wire:model.live="search" ... >

<!-- Per page dropdown -->
<select wire:model.live="perPage">
    <option value="10">10</option>
    <option value="25">25</option>
    <!-- ... -->
</select>
```

### Sorting

```php
// Via component method
$this->sortBy('name');  // Toggle asc/desc

// Via view
wire:click="sortBy('name')"
```

## Database Queries

### Get All Roles with Permissions

```php
$roles = Role::with('permissions')->get();
```

### Get Role by Name

```php
$role = Role::where('name', 'moderator')->first();
```

### Sync Permissions to Role

```php
$role->syncPermissions(['create_posts', 'edit_posts']);
```

### Assign Role to User

```php
$user->assignRole('moderator');
$user->assignRole(['moderator', 'editor']);
```

### Check User Role

```php
$user->hasRole('moderator');           // boolean
$user->hasAnyRole(['moderator', 'editor']); // boolean
$user->hasAllRoles(['moderator', 'editor']); // boolean
```

### Check User Permission

```php
$user->hasPermissionTo('create_posts');
$user->hasPermission('create_posts');
$user->can('create_posts');
```

## View Usage

### Display Role Name

```blade
{{ ucfirst(str_replace('_', ' ', $role->name)) }}
<!-- Output: 'super admin' from 'super_admin' -->
```

### List All Permissions

```blade
@foreach ($role->permissions as $permission)
    <span>{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
@endforeach
```

### Count Users with Role

```blade
<span>{{ $role->users()->count() }}</span>
```

### Show Protected Badge

```blade
@if (in_array($role->name, ['super_admin', 'admin']))
    <span class="badge badge-warning">Protected</span>
@endif
```

## Form Validation Display

```blade
@error('name')
    <p class="text-red-500 text-xs">{{ $message }}</p>
@enderror
```

## Component Properties

```php
// Current state
$this->roleId;                // ID role sedang diedit
$this->isEdit;                // true = edit mode, false = create mode
$this->showModal;             // true = modal terbuka
$this->showModalDetail;       // true = detail modal terbuka

// Form data
$this->name;                  // Nama role
$this->description;           // Deskripsi
$this->selectedPermissions;   // Array permission IDs

// UI state
$this->search;                // Search query
$this->perPage;               // Pagination size
$this->sortField;             // Field to sort by
$this->sortDirection;         // 'asc' or 'desc'
```

## Livewire Events

```php
// Dispatch success message
$this->dispatch('success', 'Role berhasil dibuat!');

// Dispatch error message
$this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
```

## Wire Directives

```blade
<!-- Two-way binding -->
wire:model="name"
wire:model.live="search"

<!-- Event listeners -->
wire:click="edit({{ $id }})"
wire:click="delete({{ $id }})"
wire:confirm="Yakin hapus?"

<!-- Form submission -->
wire:submit.prevent="save"
```

## Modal Structure

```blade
@if ($showModal)
    <!-- Backdrop -->
    <div ... wire:click="closeModal"></div>
    
    <!-- Modal content -->
    <div @click.stop>
        <!-- Form fields here -->
        <button wire:click="closeModal">Close</button>
        <button wire:click="save">Save</button>
    </div>
@endif
```

## Common Patterns

### Check if Can Create Roles

```blade
@if (auth()->user()->can('create_roles'))
    <button wire:click="openModal">Add Role</button>
@endif
```

### Disable Protected Roles Edit

```blade
@if (!in_array($role->name, ['super_admin', 'admin']))
    <button wire:click="edit({{ $role->id }})">Edit</button>
@else
    <span class="text-yellow-600">Protected</span>
@endif
```

### Show User Count Badge

```blade
<span class="badge badge-purple">
    {{ $role->users()->count() }} users
</span>
```

### Permission Grouping

```blade
@foreach ($permissions as $guardName => $guardPermissions)
    <p>{{ $guardName }}</p>
    @foreach ($guardPermissions as $permission)
        <label>
            <input type="checkbox" wire:model="selectedPermissions" 
                   value="{{ $permission->id }}">
            {{ $permission->name }}
        </label>
    @endforeach
@endforeach
```

## Error Messages

```php
// Validation errors
'name' => 'The name field is required.'
'name' => 'The name has already been taken.'
'description' => 'The description may not be greater than 1000 characters.'

// Business logic errors
'Role tidak ditemukan'
'Role ini tidak dapat dihapus!'
'Terjadi kesalahan: ...'
```

## Route Access

```
GET /admin/roles                    // List all roles (RoleIndex component)
POST /livewire/message              // Livewire actions via AJAX
```

## Testing

```php
// Create test role
$role = Role::create(['name' => 'test_role']);

// Assign permission
$permission = Permission::create(['name' => 'test_permission']);
$role->givePermissionTo($permission);

// Test user assignment
$user->assignRole($role);
$this->assertTrue($user->hasRole('test_role'));
$this->assertTrue($user->hasPermissionTo('test_permission'));
```

## Performance Tips

1. Use `->with('permissions')` untuk eager load
2. Limit pagination ke max 100 items
3. Cache permission checks: `$user->can('permission')` caches
4. Use Role/Permission model scopes for filtering
5. Index foreign keys dan frequently searched columns

## Troubleshooting Commands

```bash
# Clear permission cache
php artisan cache:forget spatie.permission.cache

# Regenerate permission cache
php artisan permission:cache-reset

# Create missing permissions/roles
php artisan db:seed PermissionSeeder --force

# Check role-user assignments
php artisan tinker
>>> User::find(1)->getRoleNames()
>>> User::find(1)->getPermissionNames()
```

## Middleware Usage

```php
// Check role
Route::get('/admin', function () {})->middleware('role:admin');

// Check permission
Route::get('/edit', function () {})->middleware('permission:edit_posts');

// Check multiple
Route::get('/moderate', function () {})
    ->middleware('role:admin|moderator');

// Multiple permissions (all required)
Route::get('/publish', function () {})
    ->middleware('permission:create_posts|publish_posts');
```

## Links & Resources

- Spatie Docs: https://spatie.be/docs/laravel-permission/v6/introduction
- Livewire Docs: https://livewire.laravel.com
- Tailwind CSS: https://tailwindcss.com
- Laravel Docs: https://laravel.com/docs

## Support & Debugging

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check database
php artisan tinker
>>> Role::all()
>>> Permission::all()
>>> Role::where('name', 'admin')->with('permissions')->first()
```
