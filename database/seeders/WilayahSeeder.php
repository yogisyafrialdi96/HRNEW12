<?php

namespace Database\Seeders;

use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kabupaten;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        $this->importCsv('provinces', function ($row) {
            Provinsi::create([
                'id' => $row[0],
                'nama' => $row[1],
            ]);
        });

        $this->importCsv('regencies', function ($row) {
            Kabupaten::create([
                'id' => $row[0],
                'provinsi_id' => $row[1],
                'nama' => $row[2],
            ]);
        });

        $this->importCsv('districts', function ($row) {
            Kecamatan::create([
                'id' => $row[0],
                'kabupaten_id' => $row[1],
                'nama' => $row[2],
            ]);
        });

        $this->importCsv('villages', function ($row) {
            Desa::create([
                'id' => $row[0],
                'kecamatan_id' => $row[1],
                'nama' => $row[2],
            ]);
        });
    }

    protected function importCsv(string $filename, callable $callback)
    {
        $path = database_path("csv/{$filename}.csv");

        if (!file_exists($path)) {
            $this->command->error("File not found: {$filename}.csv");
            return;
        }

        $file = fopen($path, 'r');

        // Skip header
        fgetcsv($file);

        DB::beginTransaction();

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Lewati jika kolom kurang
            if (count($row) < 2) continue;

            $callback($row);
        }

        DB::commit();
        fclose($file);
    }
}
