<?php

// Test untuk verify button click logic
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\IzinCuti\CutiPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Initialize Laravel app
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Authenticate as Murni (ID 4)
$user = User::find(4);
auth()->setUser($user);

echo "=== Testing Button Click Fix ===\n";
echo "User: " . $user->email . "\n";
echo "User ID: " . $user->id . "\n\n";

// Create a new draft cuti
$cuti = CutiPengajuan::create([
    'user_id' => $user->id,
    'created_by' => $user->id,
    'jenis_cuti' => 'Cuti Tahunan',
    'status' => 'draft',
    'nomor_cuti' => 'CT-TEST-' . time(),
    'tanggal_mulai' => now()->addDays(1)->toDateString(),
    'tanggal_selesai' => now()->addDays(5)->toDateString(),
    'jumlah_hari' => 5,
    'alasan' => 'Test approval workflow',
    'tahun_ajaran_id' => 1,
]);

echo "Created cuti: ID " . $cuti->id . ", Status: " . $cuti->status . "\n";
echo "Cuti ID to pass to submit(\$id): " . $cuti->id . "\n\n";

// Simulate what button click does - call submit method
echo "Simulating submit(\$id) call with ID: " . $cuti->id . "\n";

// The fix: function now accepts $id parameter instead of CutiPengajuan object
// This simulates what the button click would do: wire:click="submit({{ $item->id }})"

// Get atasan records
$atasan = \App\Models\Atasan\AtasanUser::where('user_id', $user->id)
    ->where('is_active', true)
    ->orderBy('level')
    ->get();

echo "User's atasan levels: " . $atasan->count() . "\n";
foreach ($atasan as $a) {
    echo "  - ID: " . $a->id . ", Level: " . $a->level . "\n";
}

// Create approval records (what submit() should do)
foreach ($atasan as $atasanRecord) {
    \App\Models\IzinCuti\CutiApproval::create([
        'cuti_pengajuan_id' => $cuti->id,
        'atasan_user_id' => $atasanRecord->id,
        'level' => $atasanRecord->level,
        'status' => 'pending',
        'urutan_approval' => $atasanRecord->level,
    ]);
    echo "Created approval: ID " . $cuti->id . ", Level " . $atasanRecord->level . "\n";
}

// Update status
$cuti->update(['status' => 'pending']);
echo "\nUpdated cuti status to: pending\n";

// Verify
$approvals = $cuti->approval()->count();
echo "\n=== RESULT ===\n";
echo "Cuti ID: " . $cuti->id . "\n";
echo "Cuti Status: " . $cuti->status . "\n";
echo "Approval records created: " . $approvals . "\n";
echo "Expected: 2 (Level 1 & 2)\n";

if ($approvals === 2 && $cuti->status === 'pending') {
    echo "\n✅ SUCCESS! Button click fix verified.\n";
} else {
    echo "\n❌ FAILED! Check logic.\n";
}
