<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\IzinCuti\CutiPengajuan;
use App\Models\IzinCuti\CutiApproval;
use App\Models\Master\TahunAjaran;
use App\Models\IzinCuti\CutiSaldo;

// Simulate user Murni (ID: 4) submitting cuti
$user = User::find(4); // Murni
echo "Testing submit for user: {$user->name} (ID: {$user->id})\n";

// Create a new cuti pengajuan
$tahunAjaran = TahunAjaran::where('is_active', true)->first();
$cutiSaldo = CutiSaldo::firstOrCreate(
    ['user_id' => $user->id, 'tahun_ajaran_id' => $tahunAjaran->id],
    ['cuti_tahunan_awal' => 12, 'cuti_tahunan_sisa' => 12]
);

$cuti = CutiPengajuan::create([
    'user_id' => $user->id,
    'cuti_saldo_id' => $cutiSaldo->id,
    'tahun_ajaran_id' => $tahunAjaran->id,
    'jenis_cuti' => 'tahunan',
    'status' => 'draft',
    'tanggal_mulai' => '2025-12-15',
    'tanggal_selesai' => '2025-12-17',
    'jumlah_hari' => 3,
    'alasan' => 'Test approval',
    'created_by' => $user->id,
    'updated_by' => $user->id,
]);

echo "Created cuti pengajuan: ID {$cuti->id}, Status: {$cuti->status}\n";

// Now simulate submit
$atasan = \App\Models\Atasan\AtasanUser::where('user_id', $user->id)
    ->where('is_active', true)
    ->orderBy('level')
    ->get();

echo "Atasan count: {$atasan->count()}\n";

if ($atasan->count() === 0) {
    echo "ERROR: No atasan found!\n";
} else {
    foreach ($atasan as $au) {
        $approval = CutiApproval::create([
            'cuti_pengajuan_id' => $cuti->id,
            'atasan_user_id' => $au->id,
            'level' => $au->level,
            'status' => 'pending',
            'urutan_approval' => $au->level,
        ]);
        echo "Created approval: ID {$approval->id}, Level {$au->level}\n";
    }
    
    $cuti->update(['status' => 'pending']);
    echo "Updated cuti status to: pending\n";
}

// Check results
echo "\n=== RESULTS ===\n";
$cutiApprovals = CutiApproval::where('cuti_pengajuan_id', $cuti->id)->get();
echo "Cuti approvals created: {$cutiApprovals->count()}\n";

$cutiUpdated = CutiPengajuan::find($cuti->id);
echo "Cuti final status: {$cutiUpdated->status}\n";
