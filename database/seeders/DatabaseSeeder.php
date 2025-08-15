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
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'info@ykpialittihad.or.id',
        ]);

        // 9 user random
        User::factory(9)->create();

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
            PengurusSeeder::class,
            TahunAjaranSeeder::class,
        ]);
    }
}
