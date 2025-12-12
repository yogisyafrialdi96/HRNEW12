<?php

namespace Database\Seeders;

use App\Models\Master\StatusKawin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 user admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $this->call([
            WilayahSeeder::class,
            InstansiSeeder::class,
            DepartmentSeeder::class,
            UnitSeeder::class,
            JabatanSeeder::class,
            MapelSeeder::class,
            StatusKawinSeeder::class,
            KontrakSeeder::class,
            GolonganSeeder::class,
            StatusPegawaiSeeder::class,
            EducationLevelSeeder::class,
            TahunAjaranSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PengurusSeeder::class,
            AtasanUserSeeder::class,
        ]);

        // Assign super_admin role to Admin user
        $admin->assignRole('super_admin');
    }
}
