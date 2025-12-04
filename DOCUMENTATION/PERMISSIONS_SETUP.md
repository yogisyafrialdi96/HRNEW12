# Permission Management Integration Guide

## Overview
Sistem manajemen permission dan role menggunakan Spatie Laravel Permission package yang sudah terintegrasi dengan sistem HR.

## Fitur Utama

### 1. Permission Component (`PermissionIndex.php`)
- **CRUD Operations**: Create, Read, Update, Delete permissions
- **Assign Roles**: Assign permissions ke multiple roles
- **Module-based Grouping**: Permissions diorganisir berdasarkan module
- **Advanced Search & Filter**: Cari berdasarkan nama, deskripsi, dan module
- **Detail View**: Lihat detail permission dan roles yang di-assign

### 2. Role Component (Integration)
- **CRUD Roles**: Manage roles dengan permissions
- **Permission Assignment**: Assign permissions saat membuat/edit role
- **Protected Roles**: Super admin dan admin role tidak bisa dihapus
- **Soft Delete**: Role support soft delete

### 3. Permission Modules
Sistem menggunakan naming convention `module.action`:

```
users.view          - View users list
users.create        - Create new user
users.edit          - Edit user
users.delete        - Delete user
users.restore       - Restore soft-deleted user
users.force_delete  - Permanently delete user

roles.view          - View roles
roles.create        - Create role
roles.edit          - Edit role
roles.delete        - Delete role

permissions.view    - View permissions
permissions.create  - Create permission
permissions.edit    - Edit permission
permissions.delete  - Delete permission
permissions.assign  - Assign permissions

dashboard.view      - View dashboard
dashboard.export    - Export dashboard data

employees.view      - View employees
employees.create    - Create employee
employees.edit      - Edit employee
employees.delete    - Delete employee
employees.export    - Export data

contracts.view      - View contracts
contracts.create    - Create contract
contracts.edit      - Edit contract
contracts.delete    - Delete contract
contracts.print     - Print contract
contracts.approve   - Approve contract

dan seterusnya...
```

## Setup Instructions

### 1. Run Database Migration
```bash
php artisan migrate
```

### 2. Seed Initial Data
```bash
php artisan db:seed --class=PermissionSeeder
```

Ini akan membuat permissions dan 5 default roles:
- **super_admin**: Akses penuh ke semua fitur
- **admin**: Akses ke semua fitur kecuali settings
- **manager**: Akses view, edit, export data
- **staff**: Akses basic view
- **viewer**: Read-only access

### 3. Assign Role ke User
Di aplikasi Anda, assign role ke user:
```php
$user->assignRole('manager');
// atau
$user->syncRoles(['manager', 'staff']);
```

### 4. Access Routes
- **Permissions Management**: `/admin/permissions`
- **Roles Management**: `/admin/roles`

## Usage Examples

### 1. Check Permission di Controller
```php
// Middleware check
Route::get('/admin/users', UserIndex::class)
    ->middleware('permission:users.view');

// Gate check
if (Gate::allows('users.view')) {
    // User has permission
}

// Direct check
if (auth()->user()->hasPermissionTo('users.view')) {
    // User has permission
}
```

### 2. Check Permission di Blade
```blade
@hasPermission('users.view')
    <div>User dapat melihat users</div>
@endhasPermission

@hasAnyPermission('users.view', 'users.edit')
    <div>User memiliki salah satu permission</div>
@endhasAnyPermission

@hasAllPermissions('users.view', 'users.edit')
    <div>User memiliki semua permission</div>
@endhasAllPermissions

@hasRole('admin')
    <div>User adalah admin</div>
@endhasRole
```

### 3. Using Permission Helper
```php
use App\Helpers\PermissionHelper;

// Get all permissions grouped by module
$permissions = PermissionHelper::getPermissionsByModule('users');

// Get available modules
$modules = PermissionHelper::getAvailableModules();

// Generate permission name
$permName = PermissionHelper::generatePermissionName('users', 'view');

// Check user permissions
if (PermissionHelper::userHasAnyPermission($user, ['users.view', 'users.edit'])) {
    // ...
}

// Get statistics
$stats = PermissionHelper::getPermissionStatistics();

// Copy permissions from one role to another
PermissionHelper::copyPermissionsFromRole($sourceRoleId, $targetRoleId);
```

## UI Components

### Permission Index Page
Halaman utama untuk manage permissions dengan fitur:

#### Toolbar
- **Search**: Cari permission by name atau description
- **Module Filter**: Filter by module (users, roles, employees, etc)
- **Per Page**: Pilih jumlah item per halaman (10, 25, 50, 100)
- **Add Permission**: Tombol untuk create permission baru

#### Table Columns
- **No**: Nomor urut
- **Name**: Permission name dengan badge
- **Module**: Module yang permission ini terjadi ke
- **Description**: Deskripsi permission
- **Roles**: Jumlah roles yang punya permission ini
- **Actions**: Detail, Assign Roles, Edit, Delete

#### Modals
1. **Create/Edit Modal**: Form untuk create atau edit permission
2. **Detail Modal**: View detail permission dan list roles yang di-assign
3. **Assign Roles Modal**: Checkbox list untuk assign roles

## Best Practices

### 1. Permission Naming Convention
Gunakan format `module.action`:
```
✓ users.view, users.create, users.edit, users.delete
✗ view_users, create_user, EditUser
```

### 2. Group Permissions by Module
Saat create permission, pastikan group-nya di satu module:
```
users.*         - Semua user permissions
employees.*     - Semua employee permissions
contracts.*     - Semua contract permissions
```

### 3. Assign Permissions to Role
Jangan assign permission individual, gunakan bulk assign:
```php
// Good
$role->syncPermissions(['users.view', 'users.create', 'users.edit']);

// Avoid
$role->givePermissionTo('users.view');
$role->givePermissionTo('users.create');
$role->givePermissionTo('users.edit');
```

### 4. Cache Management
Spatie caches permissions. Clear cache setelah perubahan:
```php
// Automatic (di komponten sudah handle)
app()['cache']->forget('spatie.permission.cache');

// Manual
php artisan cache:clear
```

### 5. Protect Critical Roles
Jangan biarkan user delete critical roles:
```php
if (in_array($role->name, ['super_admin', 'admin'])) {
    return error('Cannot delete protected role');
}
```

## Advanced Features

### 1. Dynamic Permission Creation
```php
// Create permission untuk modul baru
$actions = ['view', 'create', 'edit', 'delete'];
foreach ($actions as $action) {
    Permission::firstOrCreate([
        'name' => "reports.{$action}",
        'description' => ucfirst($action) . " reports"
    ]);
}
```

### 2. Permission Statistics
```php
$stats = PermissionHelper::getPermissionStatistics();
// Returns:
// [
//     'total_permissions' => 50,
//     'total_roles' => 5,
//     'permissions_by_module' => [...],
//     'avg_permissions_per_role' => 15
// ]
```

### 3. Bulk Operations
```php
// Copy semua permissions dari role A ke role B
PermissionHelper::copyPermissionsFromRole($roleA->id, $roleB->id);

// Get unassigned permissions
$unassigned = PermissionHelper::getUnassignedPermissions($roleId);

// Bulk assign
PermissionHelper::bulkAssignPermissionsToRole($roleId, $permissionIds);
```

### 4. Export/Import Permissions
```php
// Export untuk backup
$data = PermissionHelper::exportPermissions();

// Import dari backup
PermissionHelper::importPermissions($data);
```

## Middleware Protection

### Protect Routes dengan Permission
```php
// Single permission
Route::get('/admin/users', UserIndex::class)
    ->middleware('permission:users.view');

// Multiple permissions (OR)
Route::post('/admin/users', StoreUser::class)
    ->middleware('permission:users.create|users.edit');

// Multiple permissions (AND)
Route::post('/admin/users/approve', ApproveUser::class)
    ->middleware('permission:users.edit,users.approve');
```

### Protect Routes dengan Role
```php
Route::get('/admin/settings', SettingsPage::class)
    ->middleware('role:super_admin|admin');
```

## Integration dengan Components

### Check Permission di Livewire Component
```php
class UserIndex extends Component
{
    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo('users.edit')) {
            $this->dispatch('error', 'Anda tidak memiliki permission untuk edit user');
            return;
        }
        
        // Continue with edit logic
    }
}
```

### Show/Hide UI Elements Berdasarkan Permission
```blade
@hasPermission('users.create')
    <button wire:click="openModal">Add User</button>
@endhasPermission

@hasPermission('users.edit')
    <button wire:click="edit({{ $user->id }})">Edit</button>
@endhasPermission

@hasPermission('users.delete')
    <button wire:click="delete({{ $user->id }})">Delete</button>
@endhasPermission
```

## Troubleshooting

### 1. Permission tidak terlihat
- Clear cache: `php artisan cache:clear`
- Re-run seeder: `php artisan db:seed --class=PermissionSeeder`

### 2. Role tidak mendapat permission
- Check di database apakah permission sudah ada
- Pastikan user punya role yang correct
- Clear permission cache

### 3. Blade directive tidak bekerja
- Pastikan `AppServiceProvider` sudah diupdate
- Clear cache: `php artisan config:cache`

## File Structure
```
app/
├── Livewire/
│   ├── Permissions/
│   │   └── PermissionIndex.php          # Permission management component
│   └── Roles/
│       └── RoleIndex.php                # Role management component (updated)
├── Helpers/
│   └── PermissionHelper.php             # Permission utility functions
├── Providers/
│   └── AppServiceProvider.php           # Blade directives & boot config
resources/
└── views/
    └── livewire/
        └── permissions/
            └── permission-index.blade.php   # Permission management view
database/
└── seeders/
    └── PermissionSeeder.php             # Permission & role seeding
routes/
└── web.php                              # Routes (updated with /permissions)
```

## Next Steps

1. Run migrations: `php artisan migrate`
2. Seed data: `php artisan db:seed --class=PermissionSeeder`
3. Access `/admin/permissions` untuk manage permissions
4. Access `/admin/roles` untuk manage roles
5. Integrate permissions checks di aplikasi Anda

## Support

Untuk pertanyaan atau issues, silakan:
1. Check database schema di migration file
2. Verify Spatie package documentation
3. Check error logs di `storage/logs/`
