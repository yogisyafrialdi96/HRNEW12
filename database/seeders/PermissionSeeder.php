<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions grouped by module
        $permissions = [
            // Users Management
            'users.create' => 'Create user',
            'users.view' => 'View users',
            'users.edit' => 'Edit user',
            'users.delete' => 'Delete user',
            'users.restore' => 'Restore user',
            'users.force_delete' => 'Force delete user',
            'users.export' => 'Export users',
            'users.import' => 'Import users',
            'users.assign_roles' => 'Assign roles to users',

            // Roles Management
            'roles.create' => 'Create role',
            'roles.view' => 'View roles',
            'roles.edit' => 'Edit role',
            'roles.delete' => 'Delete role',

            // Permissions Management
            'permissions.create' => 'Create permission',
            'permissions.view' => 'View permissions',
            'permissions.edit' => 'Edit permission',
            'permissions.delete' => 'Delete permission',
            'permissions.assign' => 'Assign permissions to roles',

            // Dashboard
            'dashboard.view' => 'View dashboard',
            'dashboard_admin.view' => 'View admin dashboard',
            'dashboard.export' => 'Export dashboard data',

            // Karyawan Management
            'karyawan.create' => 'Create employee',
            'karyawan.view' => 'View karyawan profile',
            'karyawan.view_list' => 'View karyawan list/table',
            'karyawan.edit' => 'Edit employee',
            'karyawan.edit_own_profile' => 'Edit own profile',
            'karyawan.delete' => 'Delete employee',
            'karyawan.export' => 'Export karyawan',
            'karyawan.import' => 'Import karyawan',

            // Karyawan Pendidikan Tab
            'karyawan_pendidikan.view' => 'View karyawan education',
            'karyawan_pendidikan.create' => 'Create karyawan education',
            'karyawan_pendidikan.edit' => 'Edit karyawan education',
            'karyawan_pendidikan.delete' => 'Delete karyawan education',

            // Karyawan Organisasi Tab
            'karyawan_organisasi.view' => 'View karyawan organizations',
            'karyawan_organisasi.create' => 'Create karyawan organization',
            'karyawan_organisasi.edit' => 'Edit karyawan organization',
            'karyawan_organisasi.delete' => 'Delete karyawan organization',

            // Karyawan Pekerjaan Tab
            'karyawan_pekerjaan.view' => 'View karyawan work history',
            'karyawan_pekerjaan.create' => 'Create karyawan work record',
            'karyawan_pekerjaan.edit' => 'Edit karyawan work record',
            'karyawan_pekerjaan.delete' => 'Delete karyawan work record',

            // Karyawan Keluarga Tab
            'karyawan_keluarga.view' => 'View karyawan family',
            'karyawan_keluarga.create' => 'Create karyawan family member',
            'karyawan_keluarga.edit' => 'Edit karyawan family member',
            'karyawan_keluarga.delete' => 'Delete karyawan family member',

            // Karyawan Bahasa Tab
            'karyawan_bahasa.view' => 'View karyawan languages',
            'karyawan_bahasa.create' => 'Create karyawan language',
            'karyawan_bahasa.edit' => 'Edit karyawan language',
            'karyawan_bahasa.delete' => 'Delete karyawan language',

            // Karyawan Sertifikasi Tab
            'karyawan_sertifikasi.view' => 'View karyawan certifications',
            'karyawan_sertifikasi.create' => 'Create karyawan certification',
            'karyawan_sertifikasi.edit' => 'Edit karyawan certification',
            'karyawan_sertifikasi.delete' => 'Delete karyawan certification',

            // Karyawan Pelatihan Tab
            'karyawan_pelatihan.view' => 'View karyawan training',
            'karyawan_pelatihan.create' => 'Create karyawan training',
            'karyawan_pelatihan.edit' => 'Edit karyawan training',
            'karyawan_pelatihan.delete' => 'Delete karyawan training',

            // Karyawan Prestasi Tab
            'karyawan_prestasi.view' => 'View karyawan achievements',
            'karyawan_prestasi.create' => 'Create karyawan achievement',
            'karyawan_prestasi.edit' => 'Edit karyawan achievement',
            'karyawan_prestasi.delete' => 'Delete karyawan achievement',

            // Karyawan Dokumen Tab
            'karyawan_dokumen.view' => 'View karyawan documents',
            'karyawan_dokumen.create' => 'Upload karyawan document',
            'karyawan_dokumen.edit' => 'Edit karyawan document',
            'karyawan_dokumen.delete' => 'Delete karyawan document',

            // Karyawan Bank Tab
            'karyawan_bank.view' => 'View karyawan bank accounts',
            'karyawan_bank.create' => 'Create karyawan bank account',
            'karyawan_bank.edit' => 'Edit karyawan bank account',
            'karyawan_bank.delete' => 'Delete karyawan bank account',

            // Karyawan Jabatan Tab
            'karyawan_jabatan.view' => 'View karyawan position history',
            'karyawan_jabatan.create' => 'Create karyawan position',
            'karyawan_jabatan.edit' => 'Edit karyawan position',
            'karyawan_jabatan.delete' => 'Delete karyawan position',

            // Karyawan Kontrak Tab
            'karyawan_kontrak.view' => 'View karyawan contracts',
            'karyawan_kontrak.create' => 'Create karyawan contract',
            'karyawan_kontrak.edit' => 'Edit karyawan contract',
            'karyawan_kontrak.delete' => 'Delete karyawan contract',
            'karyawan_kontrak.dokumen_upload' => 'Upload karyawan contract document',
            'karyawan_kontrak.dokumen_download' => 'Download karyawan contract document',
            'karyawan_kontrak.dokumen_delete' => 'Delete karyawan contract document',
            'karyawan_kontrak.print' => 'Print karyawan contract',

            // Pengurus Management
            'pengurus.create' => 'Create Pengurus',
            'pengurus.view' => 'View pengurus',
            'pengurus.edit' => 'Edit pengurus',
            'pengurus.delete' => 'Delete pengurus',
            'pengurus.export' => 'Export pengurus',
            'pengurus.import' => 'Import pengurus',

            // Kontrak Management
            'kontrak_kerja.create' => 'Create contract',
            'kontrak_kerja.view' => 'View kontrak_kerja',
            'kontrak_kerja.edit' => 'Edit contract',
            'kontrak_kerja.delete' => 'Delete contract',
            'kontrak_kerja.print' => 'Print contract',
            'kontrak_kerja.approve' => 'Approve contract',

            // masakerja Management
            'masakerja.view' => 'View masakerja',

            // Attendance Management
            'attendance.view' => 'View attendance',
            'attendance.create' => 'Record attendance',
            'attendance.edit' => 'Edit attendance',
            'attendance.delete' => 'Delete attendance',
            'attendance.export' => 'Export attendance',

            // Master Data
            'master_data.create' => 'Create master data',
            'master_data.view' => 'View master data',
            'master_data.edit' => 'Edit master data',
            'master_data.delete' => 'Delete master data',

            // Reports
            'reports.view' => 'View reports',
            'reports.export' => 'Export reports',
            'reports.print' => 'Print reports',

            // Settings
            'settings.view' => 'View settings',
            'settings.edit' => 'Edit settings',
        ];

        // Create all permissions
        foreach ($permissions as $name => $description) {
            Permission::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }

        // Create roles and assign permissions
        
        // Super Admin - all permissions
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['description' => 'Super administrator dengan akses penuh']
        );
        $superAdminRole->syncPermissions(array_keys($permissions));

        // Admin - most permissions except settings
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['description' => 'Administrator dengan akses terbatas']
        );
        $adminPermissions = array_filter(
            array_keys($permissions),
            fn($p) => !str_contains($p, 'settings')
        );
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
            'karyawan.edit',
            'karyawan_pendidikan.view',
            'karyawan_pendidikan.create',
            'karyawan_pendidikan.edit',
            'karyawan_organisasi.view',
            'karyawan_organisasi.create',
            'karyawan_organisasi.edit',
            'karyawan_pekerjaan.view',
            'karyawan_pekerjaan.create',
            'karyawan_pekerjaan.edit',
            'karyawan_keluarga.view',
            'karyawan_keluarga.create',
            'karyawan_keluarga.edit',
            'karyawan_bahasa.view',
            'karyawan_bahasa.create',
            'karyawan_bahasa.edit',
            'karyawan_sertifikasi.view',
            'karyawan_sertifikasi.create',
            'karyawan_sertifikasi.edit',
            'karyawan_pelatihan.view',
            'karyawan_pelatihan.create',
            'karyawan_pelatihan.edit',
            'karyawan_prestasi.view',
            'karyawan_prestasi.create',
            'karyawan_prestasi.edit',
            'karyawan_dokumen.view',
            'karyawan_dokumen.create',
            'karyawan_dokumen.edit',
            'karyawan_bank.view',
            'karyawan_bank.create',
            'karyawan_bank.edit',
            'karyawan_jabatan.view',
            'karyawan_jabatan.create',
            'karyawan_jabatan.edit',
            'karyawan_kontrak.view',
            'karyawan_kontrak.create',
            'karyawan_kontrak.edit',
            'karyawan_kontrak.dokumen_upload',
            'karyawan_kontrak.dokumen_download',
            'karyawan_kontrak.dokumen_delete',
            'karyawan_kontrak.print',
            'kontrak_kerja.view',
            'kontrak_kerja.print',
            'master_data.view',
            'reports.view',
            'reports.export',
        ];
        $managerRole->syncPermissions($managerPermissions);

        // Staff - view and basic edit
        $staffRole = Role::updateOrCreate(
            ['name' => 'staff', 'guard_name' => 'web'],
            ['description' => 'Staff dengan akses dasar']
        );
        $staffPermissions = [
            'dashboard.view',
            'karyawan.view',
            'karyawan.edit_own_profile',
            // Tab personal yang bisa create staff (create)
            'karyawan_pendidikan.create',
            'karyawan_organisasi.create',
            'karyawan_pekerjaan.create',
            'karyawan_keluarga.create',
            'karyawan_bahasa.create',
            'karyawan_sertifikasi.create',
            'karyawan_pelatihan.create',
            'karyawan_prestasi.create',
            'karyawan_dokumen.create',
            'karyawan_bank.create',
            // Tab personal yang bisa diedit staff (view + edit)
            'karyawan_pendidikan.view',
            'karyawan_pendidikan.edit',
            'karyawan_keluarga.view',
            'karyawan_keluarga.edit',
            'karyawan_bahasa.view',
            'karyawan_bahasa.edit',
            'karyawan_bank.view',
            'karyawan_bank.edit',
            'karyawan_organisasi.view',
            'karyawan_organisasi.edit',
            'karyawan_pekerjaan.view',
            'karyawan_pekerjaan.edit',
            'karyawan_sertifikasi.view',
            'karyawan_sertifikasi.edit',
            'karyawan_pelatihan.view',
            'karyawan_pelatihan.edit',
            'karyawan_prestasi.view',
            'karyawan_prestasi.edit',
            'karyawan_dokumen.view',
            'karyawan_dokumen.edit',
            // Tab read-only untuk staff
            'karyawan_jabatan.view',
            'karyawan_kontrak.view',
            'karyawan_kontrak.dokumen_download',
        ];
        $staffRole->syncPermissions($staffPermissions);

        // Viewer - read-only access
        $viewerRole = Role::updateOrCreate(
            ['name' => 'viewer', 'guard_name' => 'web'],
            ['description' => 'Viewer hanya dapat melihat data']
        );
        $viewerPermissions = [
            'dashboard.view',
            'karyawan.view',
            'karyawan_jabatan.view',
            'karyawan_kontrak.view',
            'karyawan_kontrak.dokumen_download',
            'karyawan_kontrak.print',
            'kontrak_kerja.view',
            'master_data.view',
            'reports.view',
        ];
        $viewerRole->syncPermissions($viewerPermissions);
    }
}
