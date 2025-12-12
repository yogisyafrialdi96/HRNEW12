<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\IzinCuti\CutiPengajuan;

// Test 1: Check if CutiPengajuan exists
$cuti = CutiPengajuan::with(['approval.approvedBy', 'approvalHistories.user', 'user'])->first();

if($cuti) {
    echo "✓ Found cuti: " . $cuti->id . " - " . $cuti->nomor_cuti . "\n";
    echo "✓ Status: " . $cuti->status . "\n";
    echo "✓ User: " . $cuti->user?->name . "\n";
    echo "✓ Approval count: " . $cuti->approval->count() . "\n";
    echo "✓ History count: " . $cuti->approvalHistories->count() . "\n";
    
    // Test the loaded relations
    foreach($cuti->approval as $approval) {
        echo "  - Approval Level " . $approval->urutan_approval . ": " . $approval->status . " by " . $approval->approvedBy?->name . "\n";
    }
} else {
    echo "✗ No cuti pengajuan found\n";
}
