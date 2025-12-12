<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions jika belum ada
        $permissions = [
            'cuti.create',
            'cuti.edit',
            'cuti.delete',
            'cuti.view',
            'cuti.approve',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles dengan permissions
        $hrManagerRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);

        // Sync permissions untuk HR Manager
        $hrManagerRole->syncPermissions([
            'cuti.create',
            'cuti.edit',
            'cuti.delete',
            'cuti.view',
            'cuti.approve',
        ]);

        // Sync permissions untuk Staff
        $staffRole->syncPermissions([
            'cuti.create',
            'cuti.view',
        ]);

        // User data
        $users = [
            [
                'name' => 'Dewinta Untari',
                'email' => 'dewinta@example.com',
                'password' => Hash::make('password123'),
                'role' => 'HR Manager',
                'nip' => 'NIP-001',
            ],
            [
                'name' => 'Betha Feriani',
                'email' => 'betha@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'nip' => 'NIP-002',
            ],
            [
                'name' => 'Murni Piramadani',
                'email' => 'murni@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
                'nip' => 'NIP-003',
            ],
        ];

        // Create users and assign roles
        foreach ($users as $userData) {
            $roleName = $userData['role'];
            $nip = $userData['nip'];
            $userName = $userData['name'];
            
            unset($userData['role'], $userData['nip']);

            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role
            $user->syncRoles([$roleName]);

            // Create corresponding karyawan record if doesn't exist
            if (!$user->karyawan) {
                $karyawan = Karyawan::create([
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'full_name' => $userName,
                    'inisial' => strtoupper(substr($userName, 0, 1)) . strtoupper(substr(strrchr($userName, ' '), 1, 1)),
                    'gender' => 'perempuan',
                    'tgl_masuk' => now()->format('Y-m-d'),
                    'statuskaryawan_id' => 1,
                    'pndk_akhir' => 'S1',
                    'agama' => 'Islam',
                    'status_kawin' => 'lajang',
                    'alamat_ktp' => 'Alamat Default',
                ]);

                $this->command->info("✓ Karyawan '{$userName}' (NIP: {$nip}) berhasil dibuat");
            } else {
                $this->command->info("✓ Karyawan '{$userName}' sudah ada");
            }

            // Assign permissions via role
            $this->command->info("✓ User '{$userName}' dengan role '{$roleName}' berhasil dikonfigurasi");
        }

        $this->command->info("\n✅ UserSeeder selesai dijalankan!");
    }
}

