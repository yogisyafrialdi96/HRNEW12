<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Super Admin - all permissions
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['description' => 'Super administrator dengan akses penuh']
        );
        $allPermissions = Permission::pluck('name')->toArray();
        $superAdminRole->syncPermissions($allPermissions);

        // Admin - most permissions except settings
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['description' => 'Administrator dengan akses terbatas']
        );
        $adminPermissions = Permission::where('name', 'not like', 'settings.%')
            ->pluck('name')
            ->toArray();
        $adminRole->syncPermissions($adminPermissions);

        // Manager - manage data but limited
        $managerRole = Role::updateOrCreate(
            ['name' => 'manager', 'guard_name' => 'web'],
            ['description' => 'Manager dengan akses ke data']
        );
        $managerPermissions = [
            'dashboard.view',
            'users.view',
            'karyawan.view',
            'karyawan.view_list',
            'karyawan.edit',
            'karyawan.export',
            'pengurus.view',
            'pengurus.edit',
            'pengurus.export',
            'kontrak_kerja.view',
            'kontrak_kerja.print',
            'master_data.view',
            'reports.view',
            'reports.export',
        ];
        $managerRole->syncPermissions(
            Permission::whereIn('name', $managerPermissions)->pluck('name')->toArray()
        );

        // Staff - view and basic operations
        $staffRole = Role::updateOrCreate(
            ['name' => 'staff', 'guard_name' => 'web'],
            ['description' => 'Staff dengan akses dasar']
        );
        $staffPermissions = [
            'dashboard.view',
            'karyawan.view',
            'karyawan.edit_own_profile',
            'attendance.view',
            'attendance.create',
            'attendance.edit',
        ];
        $staffRole->syncPermissions(
            Permission::whereIn('name', $staffPermissions)->pluck('name')->toArray()
        );

        // Viewer - read-only access
        $viewerRole = Role::updateOrCreate(
            ['name' => 'viewer', 'guard_name' => 'web'],
            ['description' => 'Viewer hanya dapat melihat data']
        );
        $viewerPermissions = [
            'dashboard.view',
            'karyawan.view',
            'reports.view',
        ];
        $viewerRole->syncPermissions(
            Permission::whereIn('name', $viewerPermissions)->pluck('name')->toArray()
        );

        // HR Manager - specialized role for HR operations
        $hrManagerRole = Role::updateOrCreate(
            ['name' => 'hr_manager', 'guard_name' => 'web'],
            ['description' => 'HR Manager untuk manajemen karyawan']
        );
        $hrManagerPermissions = [
            'dashboard.view',
            'dashboard_admin.view',
            'users.view',
            'users.create',
            'users.edit',
            'karyawan.view',
            'karyawan.view_list',
            'karyawan.create',
            'karyawan.edit',
            'karyawan.export',
            'karyawan.import',
            'pengurus.view',
            'pengurus.create',
            'pengurus.edit',
            'pengurus.export',
            'pengurus.import',
            'kontrak_kerja.view',
            'kontrak_kerja.create',
            'kontrak_kerja.edit',
            'kontrak_kerja.print',
            'kontrak_kerja.approve',
            'master_data.view',
            'attendance.view',
            'attendance.export',
            'reports.view',
            'reports.export',
        ];
        $hrManagerRole->syncPermissions(
            Permission::whereIn('name', $hrManagerPermissions)->pluck('name')->toArray()
        );

        // Finance Manager - specialized role for finance operations
        $financeManagerRole = Role::updateOrCreate(
            ['name' => 'finance_manager', 'guard_name' => 'web'],
            ['description' => 'Finance Manager untuk keuangan']
        );
        $financeManagerPermissions = [
            'dashboard.view',
            'karyawan.view',
            'kontrak_kerja.view',
            'kontrak_kerja.print',
            'master_data.view',
            'reports.view',
            'reports.export',
            'reports.print',
        ];
        $financeManagerRole->syncPermissions(
            Permission::whereIn('name', $financeManagerPermissions)->pluck('name')->toArray()
        );
    }
}
