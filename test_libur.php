<?php
$count = \App\Models\IzinCuti\LiburNasional::where('is_active', 1)->count();
echo "Total libur aktif: " . $count . PHP_EOL;

if ($count === 0) {
    echo "Menambahkan data libur untuk testing..." . PHP_EOL;
    \App\Models\IzinCuti\LiburNasional::create([
        'nama_libur' => 'Hari Raya Idul Fitri',
        'tanggal_libur' => '2025-04-10',
        'tanggal_libur_akhir' => '2025-04-12',
        'tipe' => 'nasional',
        'is_active' => 1,
    ]);
    
    \App\Models\IzinCuti\LiburNasional::create([
        'nama_libur' => 'Hari Kemerdekaan',
        'tanggal_libur' => '2025-08-17',
        'tipe' => 'nasional',
        'is_active' => 1,
    ]);
    
    \App\Models\IzinCuti\LiburNasional::create([
        'nama_libur' => 'Hari Natal',
        'tanggal_libur' => '2025-12-25',
        'tipe' => 'nasional',
        'is_active' => 1,
    ]);
    
    echo "Data berhasil ditambahkan" . PHP_EOL;
} else {
    echo "Data sudah ada" . PHP_EOL;
}

$liburs = \App\Models\IzinCuti\LiburNasional::where('is_active', 1)->get();
echo "Daftar libur:" . PHP_EOL;
foreach($liburs as $libur) {
    echo "- " . $libur->nama_libur . " (" . $libur->tanggal_libur . ")" . PHP_EOL;
}
