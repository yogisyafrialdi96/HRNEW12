# CRUD Permission Quick Reference

## 🚀 Quick Start

```bash
# 1. Run migration
php artisan migrate

# 2. Seed permissions & roles
php artisan db:seed --class=PermissionSeeder

# 3. Access
- Permissions: http://localhost:8000/admin/permissions
- Roles: http://localhost:8000/admin/roles
```

## 📋 Permission Structure

Format: `module.action`

```
users.view, users.create, users.edit, users.delete
roles.view, roles.create, roles.edit, roles.delete
permissions.view, permissions.create, permissions.edit, permissions.delete
employees.view, employees.create, employees.edit, employees.delete
contracts.view, contracts.create, contracts.edit, contracts.delete
```

## 🎯 Default Roles

| Role | Permissions | Use Case |
|------|-------------|----------|
| **super_admin** | ALL | Owner/System Admin |
| **admin** | All except settings | Administrators |
| **manager** | View, Edit, Export | Department Managers |
| **staff** | Basic View | Regular Staff |
| **viewer** | Read-only | External Viewers |

## 💻 Code Usage

### Check Permission
```php
// Direct check
auth()->user()->hasPermissionTo('users.view')

// Check any
auth()->user()->hasAnyPermission(['users.view', 'users.edit'])

// Check all
auth()->user()->hasAllPermissions(['users.view', 'users.edit'])

// Check role
auth()->user()->hasRole('admin')
```

### Blade Templates
```blade
@hasPermission('users.view')
    <!-- Show content -->
@endhasPermission

@hasAnyPermission('users.view', 'users.edit')
    <!-- Show content if user has any of these -->
@endhasAnyPermission

@hasRole('admin')
    <!-- Show only for admins -->
@endhasRole
```

### Route Protection
```php
Route::get('/users', UserIndex::class)
    ->middleware('permission:users.view');

Route::post('/users', StoreUser::class)
    ->middleware('permission:users.create');
```

### Helper Functions
```php
use App\Helpers\PermissionHelper;

// Get all permissions for a module
PermissionHelper::getPermissionsByModule('users');

// Get available modules
PermissionHelper::getAvailableModules();

// Copy permissions between roles
PermissionHelper::copyPermissionsFromRole($sourceId, $targetId);

// Get statistics
PermissionHelper::getPermissionStatistics();
```

## 🔧 Component Methods

### PermissionIndex Component
```php
// Open create modal
openModal()

// Edit permission
edit($id)

// Save permission
save()

// Delete permission
delete($id)

// Show detail
showDetail($id)

// Assign roles to permission
openAssignRoles($id)

// Sync roles
assignRoles()
```

## 🗂️ Features

### Permission Management
- ✅ Create/Read/Update/Delete
- ✅ Filter by module
- ✅ Search by name/description
- ✅ Assign multiple roles
- ✅ Show role count
- ✅ Bulk operations

### Role Integration
- ✅ Assign permissions when creating role
- ✅ Edit role permissions
- ✅ View role details
- ✅ Protect critical roles
- ✅ Copy permissions between roles

### UI Features
- ✅ Dark mode support
- ✅ Responsive design
- ✅ Sorting & pagination
- ✅ Modal dialogs
- ✅ Real-time search
- ✅ Module grouping

## 📦 Files Structure

```
app/Livewire/Permissions/PermissionIndex.php
app/Helpers/PermissionHelper.php
app/Providers/AppServiceProvider.php (updated)
resources/views/livewire/permissions/permission-index.blade.php
database/seeders/PermissionSeeder.php
routes/web.php (updated)
DOCUMENTATION/PERMISSIONS_SETUP.md
```

## ⚡ Common Tasks

### Create New Module Permissions
```php
// In PermissionSeeder or manually
$module = 'documents';
$actions = ['view', 'create', 'edit', 'delete'];

foreach ($actions as $action) {
    Permission::firstOrCreate([
        'name' => "{$module}.{$action}",
        'description' => ucfirst($action) . " documents"
    ]);
}
```

### Assign Permissions to User via Role
```php
// Give user a role
$user->assignRole('manager');

// Give user multiple roles
$user->syncRoles(['manager', 'editor']);

// Check permission
$user->hasPermissionTo('employees.view') // true
```

### Create Custom Middleware
```php
// middleware/CheckPermission.php
public function handle($request, Closure $next, $permission) {
    if (!auth()->user()->hasPermissionTo($permission)) {
        abort(403);
    }
    return $next($request);
}

// Usage
Route::get('/path', Controller::class)
    ->middleware('check.permission:users.view');
```

## 🐛 Debug Commands

```bash
# Clear permission cache
php artisan cache:clear

# Check user roles
php artisan tinker
> auth()->user()->getRoleNames()

# Check user permissions
> auth()->user()->getPermissionNames()

# List all permissions
> Spatie\Permission\Models\Permission::all()

# List all roles
> Spatie\Permission\Models\Role::all()
```

## 🔐 Security Best Practices

1. **Always check permissions** in both backend and UI
2. **Protect routes** with middleware
3. **Use Blade directives** to hide UI elements
4. **Don't hardcode permissions** in checks
5. **Regularly audit** role assignments
6. **Cache permissions** for performance
7. **Backup permissions** regularly

## 🌐 API Integration

If building API, return user permissions with response:

```php
return response()->json([
    'user' => auth()->user(),
    'permissions' => auth()->user()->getPermissionNames(),
    'roles' => auth()->user()->getRoleNames(),
]);
```

## 📊 Statistics

Get permission system statistics:
```php
$stats = PermissionHelper::getPermissionStatistics();
// [
//     'total_permissions' => 50,
//     'total_roles' => 5,
//     'permissions_by_module' => ['users' => 6, 'roles' => 4, ...],
//     'avg_permissions_per_role' => 15.2
// ]
```

---

**Version**: 1.0  
**Last Updated**: 2025-12-01  
**Status**: Production Ready ✅
