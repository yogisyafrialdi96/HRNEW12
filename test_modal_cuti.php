<?php
// Test script untuk memastikan modal detail berhasil muncul

// Pastikan user sudah login
session_start();

echo "=== TEST MODAL DETAIL CUTI ===\n\n";

// 1. Check apakah table cuti_pengajuan ada data
$dbFile = 'database/database.sqlite'; // atau config/database.php kalau pakai MySQL
echo "1. Checking database connection...\n";

// 2. Load laravel autoloader
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// 3. Check data di table
use App\Models\IzinCuti\CutiPengajuan;
use App\Models\User;

$user = User::first();
echo "2. Current user: " . ($user ? $user->name : 'No user found') . "\n";

$cutiPengajuan = CutiPengajuan::limit(5)->get();
echo "3. Cuti Pengajuan records: " . $cutiPengajuan->count() . "\n";

if ($cutiPengajuan->count() > 0) {
    echo "\nSample data:\n";
    foreach ($cutiPengajuan as $cuti) {
        echo "- ID: {$cuti->id}, Nomor: {$cuti->nomor_cuti}, Status: {$cuti->status}\n";
    }
    
    // Test fetch detail
    $detail = CutiPengajuan::with(['approval.approvedBy', 'approvalHistories.user'])->find($cutiPengajuan[0]->id);
    echo "\nDetail test for ID {$detail->id}:\n";
    echo "- Nomor Cuti: {$detail->nomor_cuti}\n";
    echo "- Approval count: " . $detail->approval->count() . "\n";
    echo "- History count: " . $detail->approvalHistories->count() . "\n";
} else {
    echo "\n⚠️  No cuti pengajuan found. Please create one first.\n";
}
?>
