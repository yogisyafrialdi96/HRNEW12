<?php

namespace App\Helpers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;

class PermissionHelper
{
    /**
     * Get all permissions grouped by module
     */
    public static function getPermissionsByModule(?string $module = null): Collection
    {
        $query = Permission::query();

        if ($module) {
            $query->where('name', 'like', $module . '.%');
        }

        return $query->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
    }

    /**
     * Get available modules
     */
    public static function getAvailableModules(): array
    {
        return [
            'users' => 'User Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'dashboard' => 'Dashboard',
            'employees' => 'Employee Management',
            'contracts' => 'Contract Management',
            'attendance' => 'Attendance Management',
            'payroll' => 'Payroll Management',
            'reports' => 'Reports',
            'master_data' => 'Master Data',
            'settings' => 'Settings',
        ];
    }

    /**
     * Get all standard permission actions
     */
    public static function getStandardActions(): array
    {
        return [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
            'print' => 'Print',
            'approve' => 'Approve',
            'restore' => 'Restore',
            'force_delete' => 'Force Delete',
            'assign' => 'Assign',
        ];
    }

    /**
     * Generate permission name from module and action
     */
    public static function generatePermissionName(string $module, string $action): string
    {
        return strtolower($module) . '.' . strtolower($action);
    }

    /**
     * Get role with all its permissions
     */
    public static function getRoleWithPermissions(int $roleId)
    {
        return Role::with('permissions')->find($roleId);
    }

    /**
     * Get permissions not assigned to a role
     */
    public static function getUnassignedPermissions(int $roleId): Collection
    {
        $role = Role::find($roleId);
        $assignedIds = $role->permissions()->pluck('id')->toArray();
        
        return Permission::whereNotIn('id', $assignedIds)->get();
    }

    /**
     * Bulk assign permissions to role
     */
    public static function bulkAssignPermissionsToRole(int $roleId, array $permissionIds): bool
    {
        $role = Role::find($roleId);
        if (!$role) {
            return false;
        }

        $role->syncPermissions($permissionIds);
        return true;
    }

    /**
     * Copy permissions from one role to another
     */
    public static function copyPermissionsFromRole(int $sourceRoleId, int $targetRoleId): bool
    {
        $sourceRole = Role::find($sourceRoleId);
        $targetRole = Role::find($targetRoleId);

        if (!$sourceRole || !$targetRole) {
            return false;
        }

        $permissions = $sourceRole->permissions()->pluck('id')->toArray();
        $targetRole->syncPermissions($permissions);

        return true;
    }

    /**
     * Get all roles with their permission counts
     */
    public static function getRolesWithPermissionCounts()
    {
        return Role::withCount('permissions')
            ->orderBy('name')
            ->get();
    }

    /**
     * Check if user has any of given permissions
     */
    public static function userHasAnyPermission($user, array $permissions): bool
    {
        if (!$user) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of given permissions
     */
    public static function userHasAllPermissions($user, array $permissions): bool
    {
        if (!$user) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get permission statistics
     */
    public static function getPermissionStatistics(): array
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return [
            'total_permissions' => $permissions->count(),
            'total_roles' => $roles->count(),
            'permissions_by_module' => $permissions->groupBy(function ($p) {
                return explode('.', $p->name)[0];
            })->map->count(),
            'avg_permissions_per_role' => $roles->count() > 0 
                ? $roles->sum(fn($r) => $r->permissions()->count()) / $roles->count()
                : 0,
        ];
    }

    /**
     * Export permissions to array (useful for backup/migration)
     */
    public static function exportPermissions(): array
    {
        return Permission::all()->mapWithKeys(function ($permission) {
            return [$permission->name => $permission->description];
        })->toArray();
    }

    /**
     * Import permissions from array
     */
    public static function importPermissions(array $permissions): int
    {
        $created = 0;

        foreach ($permissions as $name => $description) {
            $created += Permission::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            ) ? 1 : 0;
        }

        return $created;
    }
}
