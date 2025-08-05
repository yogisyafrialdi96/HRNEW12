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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'info@ykpialittihad.or.id',
        ]);

        $this->call([
            WilayahSeeder::class,
            InstansiSeeder::class,
            DepartmentSeeder::class,
            UnitSeeder::class,
            MapelSeeder::class,
            StatusKawinSeeder::class,
        ]);
    }
}
