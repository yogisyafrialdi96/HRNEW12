<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Helpers\PermissionHelper;

class PermissionTestCommand extends Command
{
    protected $signature = 'permission:test';
    protected $description = 'Test permission system functionality';

    public function handle()
    {
        $this->info('Testing Permission Management System...');
        $this->newLine();

        // Test 1: Check permissions exist
        $this->testPermissionsExist();

        // Test 2: Check roles exist
        $this->testRolesExist();

        // Test 3: Check role-permission associations
        $this->testRolePermissions();

        // Test 4: Test permission helper
        $this->testPermissionHelper();

        // Test 5: Test user permissions
        $this->testUserPermissions();

        $this->info('All tests completed!');
    }

    private function testPermissionsExist()
    {
        $this->info('✓ Testing: Permissions exist');

        $count = Permission::count();
        
        if ($count > 0) {
            $this->line("  Found {$count} permissions");
            
            $modules = Permission::all()->groupBy(function ($p) {
                return explode('.', $p->name)[0];
            });

            foreach ($modules as $module => $perms) {
                $this->line("    - {$module}: " . $perms->count() . " permissions");
            }
        } else {
            $this->warn('  No permissions found. Run: php artisan db:seed --class=PermissionSeeder');
        }

        $this->newLine();
    }

    private function testRolesExist()
    {
        $this->info('✓ Testing: Roles exist');

        $roles = Role::all();

        if ($roles->count() > 0) {
            $this->line("  Found {$roles->count()} roles:");
            
            foreach ($roles as $role) {
                $permCount = $role->permissions()->count();
                $this->line("    - {$role->name}: {$permCount} permissions");
            }
        } else {
            $this->warn('  No roles found. Run: php artisan db:seed --class=PermissionSeeder');
        }

        $this->newLine();
    }

    private function testRolePermissions()
    {
        $this->info('✓ Testing: Role-Permission Associations');

        $roles = Role::with('permissions')->get();

        foreach ($roles as $role) {
            $permCount = $role->permissions()->count();
            
            if ($permCount == 0) {
                $this->warn("  {$role->name}: No permissions assigned");
            } else {
                $this->line("  {$role->name}:");
                
                $perms = $role->permissions()->get();
                foreach ($perms->take(3) as $perm) {
                    $this->line("    - {$perm->name}");
                }
                
                if ($perms->count() > 3) {
                    $this->line("    ... and " . ($perms->count() - 3) . " more");
                }
            }
        }

        $this->newLine();
    }

    private function testPermissionHelper()
    {
        $this->info('✓ Testing: Permission Helper Functions');

        // Test 1: Get available modules
        $modules = PermissionHelper::getAvailableModules();
        $this->line("  Available modules: " . count($modules));
        foreach ($modules as $key => $label) {
            $count = Permission::where('name', 'like', $key . '.%')->count();
            if ($count > 0) {
                $this->line("    - {$label} ({$count} permissions)");
            }
        }

        // Test 2: Permission statistics
        $stats = PermissionHelper::getPermissionStatistics();
        $this->newLine();
        $this->line("  Statistics:");
        $this->line("    - Total Permissions: {$stats['total_permissions']}");
        $this->line("    - Total Roles: {$stats['total_roles']}");
        $this->line("    - Avg Permissions per Role: " . round($stats['avg_permissions_per_role'], 2));

        // Test 3: Permission generation
        $this->newLine();
        $this->line("  Permission Name Generation:");
        $testName = PermissionHelper::generatePermissionName('users', 'view');
        $this->line("    - generatePermissionName('users', 'view') = {$testName}");

        $this->newLine();
    }

    private function testUserPermissions()
    {
        $this->info('✓ Testing: User Permissions');

        $user = User::first();

        if ($user) {
            $this->line("  Testing user: {$user->name} (ID: {$user->id})");

            $roles = $user->getRoleNames();
            $this->line("    Roles: " . ($roles->count() > 0 ? $roles->implode(', ') : 'None'));

            $permissions = $user->getPermissionNames();
            $this->line("    Permissions: {$permissions->count()} total");

            if ($permissions->count() > 0) {
                $this->line("    Sample permissions:");
                foreach ($permissions->take(3) as $perm) {
                    $this->line("      - {$perm}");
                }

                if ($permissions->count() > 3) {
                    $this->line("      ... and " . ($permissions->count() - 3) . " more");
                }
            }

            // Test permission checks
            $this->newLine();
            $this->line("    Permission Checks:");

            $testPerms = ['users.view', 'roles.edit', 'permissions.delete'];
            foreach ($testPerms as $perm) {
                $has = $user->hasPermissionTo($perm);
                $status = $has ? '✓' : '✗';
                $this->line("      {$status} {$perm}");
            }
        } else {
            $this->warn('  No users found in database');
        }

        $this->newLine();
    }
}
